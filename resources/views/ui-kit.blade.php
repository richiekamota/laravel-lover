@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="medium-10">

            <h1>UI Kit</h1>

            <h2>Buttons</h2>

            <button class="button">Basic</button>
            <button disabled class="button">Basic</button>
            <button class="button button--focused">Focused</button>

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

                <fieldset class="large-6 columns">
                    <legend>Check these out</legend>
                    <input id="checkbox1" type="checkbox"><label for="checkbox1">Checkbox 1</label>
                    <input id="checkbox2" type="checkbox"><label for="checkbox2">Checkbox 2</label>
                    <input id="checkbox3" type="checkbox"><label for="checkbox3">Checkbox 3</label>
                </fieldset>

                <div class="styled-select">
                    <select name="marital_status" required>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Widowed">Widowed</option>
                    </select>
                </div>

            </form>

        </div>
    </div>


@endsection
