@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <admin-items prop-items="{{$items}}"  prop-unit-types="{{$unitTypes}}"/>
        </div>
    </div>
@endsection