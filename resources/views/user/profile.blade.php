@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="medium-8 medium-offset-2">
      <h2>{{ Auth::user()->name() }}</h2>
      <div class="panel-body">

          This is the users profile

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
