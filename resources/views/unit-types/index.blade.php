@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <admin-unit-types prop-locations="{{$locations}}" prop-unit-types="{{$unitTypes}}"/>
        </div>
    </div>
@endsection