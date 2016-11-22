@extends('dashboard')

@section('dashboard-content')
    <admin-unit-types prop-locations="{{$locations}}" prop-unit-types="{{$unitTypes}}"/>
@endsection