@extends('layouts.app')

@section('content')
  <div class="row">
      <div class="medium-8 medium-offset-2">

          <h2>Register</h2>
          <div class="panel-body">
              <form role="form" method="POST" action="{{ url('/register') }}">
                  {{ csrf_field() }}
                  <label for="first_name" class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                    First name
                    <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required autofocus>
                    @if ($errors->has('first_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('first_name') }}</strong>
                        </span>
                    @endif
                  </label>

                  <label for="last_name" class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                    Last name
                    <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required autofocus>
                    @if ($errors->has('last_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('last_name') }}</strong>
                        </span>
                    @endif
                  </label>

                  <label for="email" class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    E-Mail Address
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
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

                  <label for="password-confirm">
                    Confirm Password
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                  </label>

                  <button type="submit" class="button success">
                      Register
                  </button>
              </form>
          </div>

      </div>
  </div>
@endsection
