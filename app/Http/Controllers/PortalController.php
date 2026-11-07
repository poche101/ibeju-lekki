<?php

namespace App\Http\Controllers;

use App\Models\{Attendance, Church, Member};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\{Rule, ValidationException};

class PortalController extends Controller
{
    public function show() { return view('portal.checkin'); }

    public function mark(Request $r)
    {
        $r->validate(['identifier' => 'required|string|max:120', 'children' => 'nullable|integer|min:0|max:30']);
        $id = trim($r->identifier);

        $m = str_contains($id, '@')
            ? Member::where('email', strtolower($id))->first()
            : Member::where('phone_key', Member::key($id))->first();

        if (! $m) {
            return redirect()->route('portal.register')->with('error', 'We couldn’t find you. Please create an account.');
        }

        Attendance::updateOrCreate(
            ['member_id' => $m->id, 'service_date' => Church::serviceDate()],
            ['church_id' => $m->church_id, 'children' => (int) $r->children]
        );

        return back()->with('ok', 'Thank you, '.explode(' ', $m->full_name)[0].'! Your attendance is recorded.');
    }

    // The public sign-up is for Ibeju Lekki Mega Church only.
    private function megaChurch(): Church
    {
        return Church::where('slug', 'ibeju-lekki-mega')->firstOrFail();
    }

    public function form()
    {
        $church = $this->megaChurch();

        return view('portal.register', ['church' => $church, 'cfg' => $church->config()]);
    }

    public function register(Request $r)
    {
        $church = $this->megaChurch();
        $groups = $church->config()['groups'];

        $data = $r->validate([
            'full_name' => 'required|string|max:120',
            'email' => ['required', 'email', function ($a, $v, $fail) {
                if (Member::where('email', strtolower($v))->exists()) $fail('This email is already registered. Please use check-in.');
            }],
            'phone' => ['required', 'string', 'min:10', 'max:20', function ($a, $v, $fail) {
                if (Member::where('phone_key', Member::key($v))->exists()) $fail('This phone is already registered. Please use check-in.');
            }],
            'group_name' => $groups ? ['required', Rule::in(array_keys($groups))] : 'required|string|max:100',
            'cell_name' => 'required|string|max:100',
        ]);

        if ($groups && ! in_array($data['cell_name'], $groups[$data['group_name']] ?? [])) {
            throw ValidationException::withMessages(['cell_name' => 'Invalid selection.']);
        }

        $data['email'] = strtolower($data['email']);
        $m = $church->members()->create($data);

        Attendance::create([
            'church_id' => $church->id, 'member_id' => $m->id,
            'service_date' => Church::serviceDate(), 'children' => (int) $r->input('children', 0),
        ]);

        return redirect()->route('portal.checkin')->with('ok', 'Welcome, '.explode(' ', $m->full_name)[0].'! Account created and attendance marked.');
    }

    public function loginForm() { return view('portal.login'); }

    public function login(Request $r)
    {
        $c = $r->validate(['email' => 'required|email', 'password' => 'required']);

        if (Auth::attempt($c, $r->boolean('remember'))) {
            $r->session()->regenerate();
            $church = Church::find(Auth::user()->church_id);
            if (! $church) {
                Auth::logout();
                return back()->withErrors(['email' => 'This account is not linked to a church.'])->onlyInput('email');
            }
            return redirect()->route('admin', $church);
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
    }

    public function adminHome()
    {
        if (! Auth::check()) return redirect()->route('portal.login');
        $church = Church::find(Auth::user()->church_id);
        abort_unless($church, 403, 'This account is not linked to a church.');
        return redirect()->route('admin', $church);
    }
}
