@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <h1>{{ Auth::user()->name() }}</h1>
            <div>
                <p>Full profile page coming soon</p>
            </div>
        </div>
    </div>
@endsection
