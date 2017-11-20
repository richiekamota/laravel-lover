@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <application-request-changes prop-application="{{$application}}"/>
        </div>
    </div>
@endsection