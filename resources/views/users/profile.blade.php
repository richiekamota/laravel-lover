@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ Auth::user()->name() }}</div>
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
        </div>
    </div>
@endsection
