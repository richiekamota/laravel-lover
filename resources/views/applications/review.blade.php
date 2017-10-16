@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <application-review prop-application="{{$application}}"/>
        </div>
    </div>
@endsection