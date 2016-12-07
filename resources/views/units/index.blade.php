@extends('dashboard')

@section('dashboard-content')
    {{-- TODO: Pass in the unit type, replace the list which gets populated with the unit, adjust the pagination. --}}
    <admin-units prop-locations="{{$locations}}" prop-unit-types="{{$unitTypes}}" prop-units="{{$units}}"/>
@endsection