@extends('layouts.page')

@section('page')

<div id="app" class="page-login">

    <div class="row page__header">
        <div class="column">
            <img src="/img/my-domain-logo-text.svg" alt="My Domain Logo">
        </div>
    </div>
    <div class="row columns">
        <div class="page__line --mt1"></div>
    </div>

    <div class="row column">
        <h1 class="page-login__title">Hello again.</h1>
    </div>

    <div class="row">

        <div class="medium-4 medium-offset-4">

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

                <div class="row column clearfix">
                    <a class="float-right" href="{{ url('/password/reset') }}">
                        Forgot Your Password?
                    </a>
                </div>

                <div class="row column --text-center --mt2">
                    <input id="remember" type="checkbox"><label for="remember">Remember Me</label>
                </div>

                <div class="row column --text-center --mt1">
                        <button type="submit" class="button button--exuberant">
                            Login
                        </button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection
