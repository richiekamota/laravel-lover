@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <admin-occupation-dates prop-locations="{{$locations}}" prop-units="{{$units}}"/>
        </div>
    </div>
@endsection