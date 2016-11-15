@extends('layouts.app')

@section('content')
<div class="row">

    <div class="medium-6 medium-offset-3">

        <h1>Login</h1>

        <form role="form" method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}

            <label for="email" class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                E-Mail Address
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </label>

            <label for="password" class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                Password
                <input id="password" type="password" class="form-control" name="password" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </label>

            <label class="checkbox">
                <input type="checkbox" name="remember"> Remember Me
            </label>

            <div class="row">
                <div class="columns">
                    <button type="submit" class="success button">
                        Login
                    </button>

                    <a class="button" href="{{ url('/password/reset') }}">
                        Forgot Your Password?
                    </a>
                </div>
            </div>

        </form>

    </div>

</div>
@endsection
