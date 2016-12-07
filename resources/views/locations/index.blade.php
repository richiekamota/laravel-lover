@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <admin-locations prop-locations="{{$locations}}"/>
        </div>
    </div>
@endsection