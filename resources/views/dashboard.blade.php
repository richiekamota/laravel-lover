@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="medium-6 medium-offset-3">
            <h1>Dashboard</h1>

            @if(!Gate::check('is-tenant'))

                <h3>Admin Dashboard in here</h3>

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

        </div>
    </div>
@endsection
