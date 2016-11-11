<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
        <nav>

          <div class="row">

            <div class="columns">
              <a href="{{ url('/') }}">
                  {{ config('app.name', 'Laravel') }}
              </a>
            </div>

            <div class="columns">
              <ul class="menu align-right">
                  <!-- Authentication Links -->
                  @if (Auth::guest())
                      <li><a href="{{ url('/login') }}">Login</a></li>
                      <li><a href="{{ url('/register') }}">Register</a></li>
                  @else
                      <li>
                          <a href="#">
                              {{ Auth::user()->name() }} <span class="caret"></span>
                          </a>
                      </li>

                      <li>
                          <a href="{{ url('/profile') }}">
                              Profile
                          </a>
                      </li>
                      <li>
                          <a href="{{ url('/logout') }}"
                              onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                              Logout
                          </a>

                          <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                          </form>
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
