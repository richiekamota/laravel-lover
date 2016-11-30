@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="medium-10">

            <h1>UI Kit</h1>

            <h2>Buttons</h2>

            <button class="button">Basic</button>
            <button disabled class="button">Basic</button>
            <button class="button focused">Focused</button>

            <h2>Form Elements</h2>

            <form action="#">

                <label for="standard" class="form-group">
                    Standard Input
                    <input id="standard" type="text" name="standard" value="This is the value" required autofocus>
                </label>

                <label for="disabled" class="form-group">
                    Disabled Input
                    <input id="disabled" type="email" name="disabled" disabled>
                </label>

                <label for="error" class="form-group has-error">
                    Input with error
                    <input id="error" type="text" name="error" value="Error" required>
                    <span class="help-block">
                        <strong>Error description and instructions to correct the error</strong>
                    </span>
                </label>

            </form>

        </div>
    </div>


@endsection
