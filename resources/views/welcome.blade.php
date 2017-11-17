@extends('layouts.header')
@section('content')
        <div class="container">
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="row">
                    <!--<div class="col-xs-12">
                        @auth
                            <a href="{{ url('/home') }}">Home</a>
                        @else
                            <a href="{{ route('login') }}">Login</a>
                            <a href="{{ route('register') }}">Register</a>
                        @endauth
                    </div>-->
                </div>
            @endif
                <div class="row">
                    <div class="col-xs-12 text-center bold">
                        Escuela del Software C.A
                    </div>
                </div>
            </div>
        </div>
@endsection
