<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">



    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">

        <div class="row expanded collapse">
            <div class="medium-3 large-2 columns admin-menu --bg-iced">
                <a href="{{ url('/') }}" class="header__logo text-center">
                    <img src="/img/logo.svg" alt="Portal Logo">
                </a>

                @if(!Gate::check('is-tenant'))
                    <ul class="admin-menu__links">
                            <li class="admin-menu__link-item @if(Request::path() == 'locations')admin-menu__link-active --bg-mist @endif"><a href="{{ url('/locations') }}" class="admin-menu__link">Add locations</a></li>
                            <li class="admin-menu__link-item @if(Request::path() == 'unit-types')admin-menu__link-active --bg-mist @endif"><a href="{{ url('/unit-types') }}" class="admin-menu__link">Add unit type</a></li>
                            <li class="admin-menu__link-item @if(Request::path() == 'units')admin-menu__link-active --bg-mist @endif"><a href="{{ url('/units') }}" class="admin-menu__link">Add units</a></li>
                        </ul>
                @endif

                @if(Gate::check('is-tenant'))
                    <ul class="admin-menu__links">
                            <li><a href="{{ url('/locations') }}" class="admin-menu__link">Application form</a></li>
                            <li><a href="{{ url('/unit-types') }}" class="admin-menu__link">Contract page</a></li>
                            <li><a href="{{ url('/units') }}" class="admin-menu__link">Profile page</a></li>
                        </ul>
                @endif
            </div>

            <div class="medium-9 large-10 columns ">
                <nav class="header --bg-breeze">

                    <div class="row align-middle">

                        <div class="columns">
                            <ul class="menu align-right">
                                <!-- Authentication Links -->
                                @if (Auth::guest())
                                    <li>
                                        <a href="{{ url('/login') }}" class="header__link header__link--nav">Login</a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                           onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();" class="header__link header__link--nav">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                    <li>
                                        <a href="{{ url('/profile') }}" class="header__link header__link--nav">
                                            Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/dashboard" class="header__link header__link--nav">
                                            Dashboard
                                        </a>
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

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
