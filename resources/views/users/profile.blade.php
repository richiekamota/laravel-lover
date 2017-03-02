@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <h1>{{ Auth::user()->name() }}</h1>
            <div>
                <p>This is the users profile</p>
                <p>Eventually the user can update their details within the system</p>
            </div>
        </div>
    </div>
@endsection
