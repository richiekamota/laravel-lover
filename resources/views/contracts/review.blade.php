@extends('layouts.app')

@section('content')

    <h2 class="--focused">CONTRACT FOR REVIEW | have a good read</h2>
    <p>
        This is your contract outlining the specifics of the room, the rules and what we expect of each other at My Domain.
        Please take the time to read through the contract and if you are happy complete the instructions at the bottom of the page.
    </p>
    <p>
        Your contract starts below the line
    </p>
    <hr>

    <div class="row">
        <div class="column medium-12">
            @include('contracts.contract')
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="column">
            <contract-approve prop-contract="{{$contract}}"/>
        </div>
    </div>


@endsection