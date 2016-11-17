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
                </div>
            </div>
        </div>
    </div>

@endsection
