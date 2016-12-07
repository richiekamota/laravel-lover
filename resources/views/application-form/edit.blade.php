@extends('layouts.page')

@section('page')

<div id="app" class="page-application">

    <div class="row page__header">
        <div class="column">
            <img src="/img/my-domain-logo-text.svg" alt="My Domain Logo">
        </div>
    </div>

    <div class="row columns">
        <div class="page__line --mt1"></div>
    </div>

    <div class="row">
        <div class="medium-8">
            <application-form step="{{$applicationForm->step}}" form-application-id="{{$applicationForm->id}}" prop-locations="{{$locations}}" prop-unit-types="{{$unitTypes}}" ></application-form>
        </div>
    </div>

</div>

@endsection