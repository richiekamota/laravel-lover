@extends('layouts.app')

@section('content')

    <div class="row column">

            <h2 class="--focused">DASHBOARD | where stuff gets done</h2>

            @if(!Gate::check('is-tenant'))

                <open-applications prop-open-applications="{{$openApplications}}"></open-applications>

                <pending-applications prop-pending-applications="{{$pendingApplications}}"></pending-applications>

            @endif

            @if(Gate::check('is-tenant'))

                <h3>Tenant Dashboard in here</h3>

                @foreach($openApplications as $application)

                    {{$application->id}} <br>
                    {{$application->status}}

                @endforeach

                {{-- Contracts Table --}}

                {{-- Applications Table --}}

            @endif

    </div>

@endsection
