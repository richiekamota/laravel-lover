@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <admin-units prop-locations="{{$locations}}" prop-unit-types="{{$unitTypes}}" prop-units="{{$units}}"/>
        </div>
    </div>
@endsection