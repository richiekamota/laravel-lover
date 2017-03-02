@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <application-user-review prop-application="{{$applicationForm}}"/>
        </div>
    </div>
@endsection