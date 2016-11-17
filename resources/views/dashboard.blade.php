@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="medium-3 columns">
            Left side bar
        </div>

        <div class="medium-9 columns">
            Main content
        </div>
    </div>

    <div class="row">
      <div class="medium-6 medium-offset-3">
          <h1>Dashboard</h1>

          <div>
              <p>
                  This is your dashboard
              </p>
              <p>
                  Soon we will be showing the list of incoming applications.
              </p>
          </div>
      </div>
    </div>
@endsection
