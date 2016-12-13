@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <application-approve prop-application="{{$application}}" prop-location="{{$location}}" prop-suggested-items="{{$suggestedItems}}" prop-items="{{$items}}"/>
        </div>
    </div>
@endsection