@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="medium-10">

            <h1>UI Kit</h1>

            <h2>Colors</h2>

            <div class="row">
                <div class="column color" style="background-color: #1b696e; height: 100px; display: flex; align-items: center; color: #fff; text-align: center">focused</div>
                <div class="column color" style="background-color: #73cfcd; height: 100px; display: flex; align-items: center; color: #fff; text-align: center">breeze</div>
                <div class="column color" style="background-color: #0afcea; height: 100px; display: flex; align-items: center; color: #000; text-align: center">amped</div>
                <div class="column color" style="background-color: #4a494b; height: 100px; display: flex; align-items: center; color: #fff; text-align: center">concrete</div>
                <div class="column color" style="background-color: #ff9f02; height: 100px; display: flex; align-items: center; color: #fff; text-align: center">alarmed</div>
                <div class="column color" style="background-color: #9a989b; height: 100px; display: flex; align-items: center; color: #fff; text-align: center">slate</div>
            </div>

            <div class="row">
                <div class="column color" style="background-color: #f73179; height: 100px; display: flex; align-items: center; color: #fff; text-align: center">exuberant</div>
                <div class="column color" style="background-color: #dfdfdf; height: 100px; display: flex; align-items: center; color: #000; text-align: center">mist</div>
                <div class="column color" style="background-color: #ff7902; height: 100px; display: flex; align-items: center; color: #fff; text-align: center">blazed</div>
                <div class="column color" style="background-color: #61aead; height: 100px; display: flex; align-items: center; color: #fff; text-align: center">leafed</div>
                <div class="column color" style="background-color: #f6f6f6; height: 100px; display: flex; align-items: center; color: #000; text-align: center">clouded</div>
                <div class="column color" style="background-color: #ffc668; height: 100px; display: flex; align-items: center; color: #fff; text-align: center">daffy</div>
            </div>

            <div class="row">
                <div class="column color" style="background-color: rgba(115,207,205,0.32); height: 100px; display: flex; align-items: center; color: #000; text-align: center">calm</div>
                <div class="column color" style="background-color: #bdfbff; height: 100px; display: flex; align-items: center; color: #000; text-align: center">iced</div>
            </div>

            <hr class="--mt2">

            <h2>Buttons</h2>

            <div class="row column">

                <button class="button">Basic</button>
                <button disabled class="button">Basic</button>
                <button class="button button--focused">--focused</button>

            </div>

            <div class="row column">

                <button class="button button--approve">--outline</button>
                <button class="button button--pending">--pending</button>
                <button class="button button--decline">--decline</button>

            </div>

            <hr class="--mt2">

            <h2>Headings</h2>

            <div class="accordion__heading accordion__heading--add">
                <h4 id="heading-user-details" class="--white">.accordion__heading .accordion__heading--add</h4>
            </div>

            <div class="title-bar --mt2">
                <h4>.title-bar</h4>
            </div>

            <hr class="--mt2">

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
