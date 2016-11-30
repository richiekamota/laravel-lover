@extends('layouts.app')

@section('content')

    <div class="row">
                <div class="medium-11 medium-offset-1">

                    @hasSection('dashboard-content')

                    @yield('dashboard-content')

                    @else

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

                        <div>
                            <p>
                                This is your dashboard
                            </p>
                            <p>
                                Soon we will be showing the list of incoming applications.
                            </p>
                        </div>

                        <div class="received-applications">
                            <!-- The Application row -->
                            <div class="application">
                                <div class="row">
                                    <div class="small-7 columns">
                                        <h3 class="application__title">Application 1</h3>
                                    </div>

                                    <div class="small-5 columns application-actions -text-right align-middle">
                                        <a href="#1">Go to application</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Duplication application row -->
                            <div class="application">
                                <div class="row">
                                    <div class="small-7 columns">
                                        <h3 class="application__title">Application 2</h3>
                                    </div>

                                    <div class="small-5 columns application-actions -text-right align-middle">
                                        <a href="#1">Go to application</a>
                                    </div>
                                </div>
                            </div>

                            <div class="application">
                                <div class="row">
                                    <div class="small-7 columns">
                                        <h3 class="application__title">Application 3</h3>
                                    </div>

                                    <div class="small-5 columns application-actions -text-right align-middle">
                                        <a href="#1">Go to application</a>
                                    </div>
                                </div>
                            </div>

                        </div>

                    @endif
                </div>
            </div>

@endsection
