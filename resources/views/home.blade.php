@extends('layout')@section('h', 'Welcome to Ibeju Lekki Mega Church')@section('sub', 'Choose your church to continue.')
@section('c')<h2>Select your church</h2>
    <p class="mut">Each church has its own private attendance space.</p>
    @foreach ($churches as $c)
        <a class="btn ghost" style="justify-content:space-between" href="{{ route('checkin', $c) }}">{{ $c->name }}
            <span>→</span></a>
    @endforeach @endsection
