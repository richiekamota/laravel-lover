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
        <nav class="header">

          <div class="row align-middle">

            <div class="columns">
              <a href="{{ url('/') }}" class="header__link">
                  {{ config('app.name', 'Laravel') }}
              </a>
            </div>

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

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
