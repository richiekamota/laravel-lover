@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <admin-users prop-users="{{$users}}"/>
        </div>
    </div>
@endsection