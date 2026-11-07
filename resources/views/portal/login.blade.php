@extends('layout')@section('h','Ibeju Lekki Mega Church')@section('sub','Administrators only. You will only ever see your own church’s data.')
@section('c')
<h2>Admin sign in</h2>
<p class="mut">One sign-in for every church admin.</p>
<form method="post" class="login">@csrf
    <div class="fgrid">
        <div class="sec">Administrator access</div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@yourchurch.org" required autofocus>
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required>
    </div>
    <button class="btn">Sign in</button>
</form>
<a class="link" href="{{ route('portal.checkin') }}">← Back to check‑in</a>
@endsection
