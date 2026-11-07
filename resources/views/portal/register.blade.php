@extends('layout')@section('h','Ibeju Lekki Mega Church')@section('sub','Create your account once. Next time, just type your email or phone.')
@section('c')
<h2>Create your account</h2>
<p class="mut">It takes less than a minute.</p>
<form class="register" method="post" action="{{ route('portal.register.store') }}">@csrf
    @include('partials.member-fields', ['m' => null])
    <div class="fgrid">
        <div class="sec" style="margin-top:14px">Family</div>
        <label>Children with you today (if any)</label>
        <input type="number" name="children" min="0" value="{{ old('children', 0) }}">
    </div>
    <button class="btn">Create account &amp; mark attendance</button>
</form>
<a class="link" href="{{ route('portal.checkin') }}">Already registered? Check in</a>
@endsection
