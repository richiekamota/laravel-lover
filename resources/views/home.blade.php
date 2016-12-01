@extends('layouts.page')

@section('page')

<div id="app" class="page-home">

    <div class="row page__header">
        <div class="column">
            <img src="/img/my-domain-logo-text.svg" alt="My Domain Logo">
        </div>
        <div class="column">
            @if(!Auth::check())
                <a href="{{ url('/login') }}">
                    <button class="button float-right">Login</button>
                </a>
            @else
                <a href="{{ url('/logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="float-right">
                    <button class="button">Logout</button>
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                      style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endif

        </div>
    </div>
    <div class="row columns">
        <div class="page__line --mt1"></div>
    </div>
    <div class="row">
        <div class="columns --text-center">
            <img src="/img/md-logo.svg" class="page-home__md-logo" alt="My Domain Logo">
            <div class="page-home__title --mt2 --concrete">
                MY DOMAIN IS YOUR DOMAIN.
            </div>
            <div class="page-home__sub-title --mt1 --concrete">
                Unique and premium student accommodation in Cape Town’s leafy Southern Suburbs
            </div>
            @if(!Auth::check())
                <div class="--text-center --mt5">
                    <a href="{{ url('/application-form') }}">
                        <button class="button button--exuberant button--large --mt3">
                            Get started
                        </button>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="page__footer">
       <div class="row">
           <div class="columns">
               <div class="page__copyright">
                   All rights reserved. © 2016
               </div>
           </div>
           <div class="columns">
               <img class="page__swish float-right" src="/img/swish-logo-white@3x.jpg" alt="Swish logo">
           </div>
       </div>
    </div>

</div>

@endsection
