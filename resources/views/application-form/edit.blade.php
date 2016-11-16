@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="medium-6 medium-offset-3">
            <application-form step="{{$applicationForm->step}}" form-application-id="{{$applicationForm->id}}"></application-form>
        </div>
    </div>

@endsection