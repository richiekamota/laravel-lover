@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <application-pending prop-application="{{$application}}" prop-location="{{$location}}" prop-unit-types="{{$unitTypes}}"/>
        </div>
    </div>
@endsection