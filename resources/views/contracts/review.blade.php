@extends('layouts.app')

@section('content')

    This is your contract {{$contract->user->first_name}}

    @include('contracts.contract')
    
    <div class="row">
        <div class="column">
            <contract-approve prop-contract="{{$contract}}"/>
        </div>
    </div>


@endsection