<?php

namespace Database\Seeders;

use App\Models\Church;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ChurchSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('churches') as $slug => $c) {
            $church = Church::updateOrCreate(['slug' => $slug], ['name' => $c['name']]);

            // Optional overrides in .env, e.g. ADMIN_EMAIL_IBEJU_LEKKI_MEGA / ADMIN_PASSWORD_IBEJU_LEKKI_MEGA
            $key = strtoupper(str_replace('-', '_', $slug));
            $email = env("ADMIN_EMAIL_{$key}", "admin@{$slug}.org");
            $envPassword = env("ADMIN_PASSWORD_{$key}");

            $user = User::where('email', $email)->first();

            if ($user && ! $envPassword) {
                // Admin already exists and no password was set in .env:
                // keep the current password, just make sure the church link is right.
                $user->forceFill(['church_id' => $church->id, 'name' => $c['name'].' Admin'])->save();
                $this->command->warn("$slug  =>  $email  (already exists, password unchanged)");
                continue;
            }

            $password = $envPassword ?: Str::password(14, symbols: false);

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $c['name'].' Admin',
                    'password' => Hash::make($password),
                    'church_id' => $church->id,
                ]
            );

            $this->command->info("$slug  =>  $email  /  $password");
        }
    }
}
