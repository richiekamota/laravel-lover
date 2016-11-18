@extends('layouts.app')

@section('content')

    <div class="content">
        <div style="font-size: 60px; font-weight: 300; text-align: center; margin-top: 30vh;">
            MyDomain Portal
        </div>

        @if(!Auth::check())
            <div style="text-align: center; margin-top: 5vh;">
                <a href="{{ url('/application-form') }}">Start Application Form</a>
            </div>
        @endif
    </div>

@endsection
