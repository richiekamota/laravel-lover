@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="medium-10">
            <admin-locations prop-locations="{{$locations}}"/>
        </div>
    </div>
@endsection