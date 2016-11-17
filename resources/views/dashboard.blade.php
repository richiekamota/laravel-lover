@extends('layouts.app')

@section('content')
    <div class="row expanded">
        <div class="medium-3 large-2 columns admin-menu">
            <h4 class="-text-center">Admin Menu</h4>
            <ul class="menu vertical admin-menu__links">
                <li><a href="#1" class="admin-menu__link admin-menu__link--active">One</a></li>
                <li><a href="#2" class="admin-menu__link">Two</a></li>
                <li><a href="#3" class="admin-menu__link">Three</a></li>
                <li><a href="#4" class="admin-menu__link">Four</a></li>
            </ul>
        </div>

        <div class="medium-9 large-10 columns">
            <div class="row">
                <div class="medium-11 medium-offset-1">
                    <h1>Dashboard</h1>

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
                                <div class="small-7 columns application-title">
                                    Application 1
                                </div>

                                <div class="small-5 columns application-actions -text-right">
                                    <a href="#1">Go to application</a>
                                </div>
                            </div>
                        </div>

                        <!-- The Application form -->
                        <div class="application-form">
                            <div class="row">
                                <div class="medium-10 columns">
                                    <h5>Application here</h5>
                                    <div class="row">
                                        <div class="medium-3 columns">
                                            <strong>Unit lease length</strong>
                                        </div>
                                        <div class="medium-9 columns">
                                            11
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="medium-3 columns">
                                            <strong>Landlord's work phone number</strong>
                                        </div>
                                        <div class="medium-9 columns">
                                            +111483148021
                                        </div>
                                    </div>
                                </div>
                                <div class="medium-2 columns application-actions -text-right">
                                    <a href="#1">Approve</a>
                                    <a href="#2">Deny</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
