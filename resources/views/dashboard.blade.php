@extends('layouts.app')

@section('content')

    <div class="row column">

            <h2 class="--focused">DASHBOARD</h2>

            @if(!Gate::check('is-tenant'))

                <open-applications prop-open-applications="{{$openApplications}}"></open-applications>

                <pending-applications prop-pending-applications="{{$pendingApplications}}"></pending-applications>

                <all-applications prop-all-applications="{{$allApplications}}"></all-applications>

                <cancelled-contracts prop-cancelled-contracts="{{$cancelledContracts}}"></cancelled-contracts>

            @endif

            @if(Gate::check('is-tenant'))

                <tenant-applications prop-applications="{{$applications}}"></tenant-applications>

                <tenant-contracts prop-contracts="{{$contracts}}"></tenant-contracts>

            @endif

    </div>

@endsection
