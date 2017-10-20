@extends('layouts.app')

@section('content')

    <div id="app">



            <application-form step="{{$applicationForm->step}}" form-application-id="{{$applicationForm->id}}"
                              prop-locations="{{$locations}}" prop-unit-types="{{$unitTypes}}"
                              application-form-data="{{$applicationForm}}"></application-form>



    </div>

@endsection