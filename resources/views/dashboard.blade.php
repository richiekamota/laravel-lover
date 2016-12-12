@extends('layouts.app')

@section('content')

    <div class="row column">

            @hasSection('dashboard-content')

                @yield('dashboard-content')

            @else

                <h2 class="--focused">DASHBOARD | where stuff gets done</h2>

                @if(!Gate::check('is-tenant'))

                    <open-applications prop-open-applications="{{$openApplications}}"/>

                    {{-- Open Applications --}}

                    {{-- Pending Applications --}}

                    {{-- Closed Applications --}}

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

            @endif
    </div>

@endsection
