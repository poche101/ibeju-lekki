@extends('layout')@section('h','Ibeju Lekki Mega Church')@section('sub','Mark your attendance in seconds, with one link for every member.')
@section('c')
@php($today = now())
<h2>Welcome, beloved</h2>
<p class="mut">Mark your attendance for <b>{{ $today->format('l, j F Y') }}</b>.</p>

<form method="post" action="{{ route('portal.mark') }}" class="checkin">@csrf
    <div class="fgrid">
        <div class="sec">Attendance check‑in</div>
        <label>Email or phone number</label>
        <input name="identifier" value="{{ old('identifier') }}" placeholder="you@email.com or 0803 000 0000" required autofocus>
        <label>Children with you (if any)</label>
        <input type="number" name="children" min="0" value="0">
    </div>
    <button class="btn">Mark my attendance</button>
</form>
<a class="link" href="{{ route('portal.register') }}">Don’t have an account?</a>
@endsection
