@extends('layouts.page')

@section('page')
    <div id="app">
        <div class="row expanded collapse">
            <div class="medium-3 large-2 columns admin-menu --bg-iced">
                <a href="{{ url('/') }}" class="header__logo text-center">
                    <img src="/img/logo.svg" alt="Portal Logo">
                </a>

                @if(!Gate::check('is-tenant'))
                    <ul class="admin-menu__links">
                        <li class="admin-menu__link-item @if(Request::path() == 'locations')admin-menu__link-active --bg-mist @endif">
                            <a href="{{ url('/locations') }}" class="admin-menu__link">Locations</a></li>
                        <li class="admin-menu__link-item @if(Request::path() == 'unit-types')admin-menu__link-active --bg-mist @endif">
                            <a href="{{ url('/unit-types') }}" class="admin-menu__link">Unit types</a></li>
                        <li class="admin-menu__link-item @if(Request::path() == 'units')admin-menu__link-active --bg-mist @endif">
                            <a href="{{ url('/units') }}" class="admin-menu__link">Units</a></li>
                        <li class="admin-menu__link-item @if(Request::path() == 'items')admin-menu__link-active --bg-mist @endif">
                            <a href="{{ url('/items') }}" class="admin-menu__link">Items</a></li>
                    </ul>
                @endif

                @if(Gate::check('is-tenant'))
                    <ul class="admin-menu__links">
                        <li class="admin-menu__link-item @if(Request::path() == '')admin-menu__link-active --bg-mist @endif">
                            <a href="{{ url('/application-form/new') }}" class="admin-menu__link">New Application</a></li>
                    </ul>
                @endif
            </div>

            <div class="medium-9 large-10 columns ">
                <nav class="header --bg-breeze">

                    <div class="row align-middle">

                        <div class="columns">
                            <ul class="menu">
                                <!-- Authentication Links -->
                                @if (Auth::guest())
                                    <li>
                                        <a href="{{ url('/login') }}" class="header__link header__link--nav">Login</a>
                                    </li>
                                @else
                                    <li>
                                        <a href="/dashboard"
                                           class="header__link header__link--nav @if(Request::path() == 'dashboard')header__link--active --bg-focused @endif">
                                            Dashboard
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ url('/profile') }}"
                                           class="header__link header__link--nav @if(Request::path() == 'profile')header__link--active --bg-focused @endif">
                                            Profile
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ url('/logout') }}"
                                           onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();"
                                           class="header__link header__link--nav">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                              style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>

                                @endif
                            </ul>
                        </div>

                    </div>

                </nav>
                <div class="main-content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endsection