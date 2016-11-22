@extends('dashboard')

@section('dashboard-content')
    <h1>Yo</h1>
    <admin-units prop-locations="{{$locations}}" prop-unit-types="{{$unitTypes}}"/>
@endsection