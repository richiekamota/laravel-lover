@extends('layouts.app')

@section('content')
<div class="row">
    <div class="medium-6 medium-offset-3">
        <h1>Reset Password</h1>

            <form role="form" method="POST" action="{{ url('/password/reset') }}">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">

                <label for="email" class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    E-Mail Address
                    <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
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

                <label for="password-confirm" class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    Confirm Password
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </label>

                <div class="row">
                    <div class="medium-6 medium-offset-4">
                        <button type="submit" class="success button">
                            Reset Password
                        </button>
                    </div>
                </div>
            </form>
    </div>
</div>
@endsection
