@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="column">
            <admin-occupation-dates prop-occupation-dates="{{$occupationDates}}"/>
        </div>
    </div>
@endsection