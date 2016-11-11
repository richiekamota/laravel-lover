@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="medium-6 medium-offset-3">
      <h1>{{ Auth::user()->name() }}</h1>

      <div>
          <p>This is the users profile</p>

          <h4>
              Tenant ID: {{ Auth::user()->tenant_id }}
          </h4>

          <p>
              There will be a list of the users contracts including links
              to download them as PDF
          </p>
      </div>
    </div>
  </div>
@endsection
