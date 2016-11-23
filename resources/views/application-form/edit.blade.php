@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="medium-8 medium-offset-2">
            <application-form step="{{$applicationForm->step}}" form-application-id="{{$applicationForm->id}}" prop-locations="{{$locations}}" prop-unit-types="{{$unitTypes}}" ></application-form>
        </div>
    </div>

@endsection