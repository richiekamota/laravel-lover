@extends('layouts.page')

@section('page')
<div id="app" class="page-application">

    <div class="row page__header">
        <div class="column">
            <img src="/img/my-domain-logo-text.svg" alt="My Domain Logo">
        </div>
    </div>
    <div class="row columns">
        <div class="page__line --mt1"></div>
    </div>

    <div class="row">
        <div class="medium-8 column">
            <div class="row column">
                <div class="page-application__header">
                    <h1 class="page-application__header-title">Application Form</h1>
                    <p class="page-application__header-sub">Thanks for choosing MyDomain as your student accommodation provider. To get started simply fill in the following application form.</p>
                    <p>The first step is to create an account with us. We use this account to communicate with you during the application process and if successful this account will be your portal to the MyDomain team.</p>
                </div>
            </div>
            <div class="row column">

                <form role="form" method="POST" action="{{ url('/application-form') }}">
                    {{ csrf_field() }}
                    <div class="application-step__heading">
                        Step 1: Create your MyDomain account
                    </div>
                    <div class="application-step__content application-step__content--active">

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

                        <hr class="application-step__line --mt2">

                        <button type="submit" class="button --mt1">
                            Save and continue
                        </button>

                        @if(!Auth::check())
                            <a href="/login" class="float-right --mt2">Already have a login?</a>
                        @endif

                    </div>
                </form>

                <div class="application-step__heading --disabled --mt1">
                    Step 2: Content
                </div>
                <div class="application-step__heading --disabled --mt1">
                    Step 3: Content
                </div>
                <div class="application-step__heading --disabled --mt1">
                    Step 4: Content
                </div>

            </div>


        </div>
    </div>
</div>
@endsection