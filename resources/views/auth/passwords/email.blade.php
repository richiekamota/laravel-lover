@extends('layouts.app')

<!-- Main Content -->
@section('content')
<div class="row">
    <div class="medium-6 medium-offset-3">
        <h1>Reset Password</h1>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form role="form" method="POST" action="{{ url('/password/email') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <div class="medium-6 medium-offset-4">
                    <button type="submit" class="success button">
                        Send Password Reset Link
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
