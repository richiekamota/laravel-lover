<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::get('/register', function () {
    return redirect('/login');
});

Route::get('/home', function () {
    return redirect('/');
});


Route::get('/', 'HomeController@index');

Route::get('ui-kit', 'DashboardController@uiKit');

/*
|--------------------------------------------------------------------------
| Application form - manage the users application form
|--------------------------------------------------------------------------
|
*/

Route::get('/application-form', 'ApplicationController@create');
Route::post('/application-form', 'ApplicationController@store');


Route::group(['middleware' => 'auth'], function () {

    // Show the users dashboard
    Route::get('/dashboard', 'DashboardController@index');

    // Show the users profile
    Route::get('/profile', 'UsersController@profile');

    /*
    |--------------------------------------------------------------------------
    | Users - manage the users of the site
    |--------------------------------------------------------------------------
    |
    */

    Route::resource('users', 'UsersController');

    /*
    |--------------------------------------------------------------------------
    | Occupation Dates - manage the occupation of units
    |--------------------------------------------------------------------------
    |
    */
    Route::post('/occupations/export', 'OccupationDateController@exportToCSV');
    Route::resource('occupations', 'OccupationDateController');
    Route::post('/occupations/{id}', 'ContractsController@store');
    Route::post('/occupations/{id}/editenddate','OccupationDateController@updateEndDate');

    /*
    |--------------------------------------------------------------------------
    | Locations - manage the locations of units
    |--------------------------------------------------------------------------
    |
    */

    Route::resource('locations', 'LocationsController');

    /*
    |--------------------------------------------------------------------------
    | Units - manage the units
    |--------------------------------------------------------------------------
    |
    */

    Route::resource('units', 'UnitsController');

    /*
    |--------------------------------------------------------------------------
    | Unit types - manage the unit types
    |--------------------------------------------------------------------------
    |
    */

    Route::resource('unit-types', 'UnitTypesController');

    /*
    |--------------------------------------------------------------------------
    | Items - manage the items a unit type might have
    |--------------------------------------------------------------------------
    |
    */

    Route::resource('items', 'ItemsController');
    Route::get('items-leased', 'ItemsController@leasedItems');
    Route::delete('items/lease/{id}', 'ItemsController@deleteItemLease');
    Route::post('items/lease', 'ItemsController@createItemLease');
    Route::delete('items/{id}/delete', 'ItemsController@deleteItem');

    /*
    |--------------------------------------------------------------------------
    | Contracts - manage the contracts
    |--------------------------------------------------------------------------
    |
    */

    Route::resource('contracts', 'ContractsController');

    Route::get('/contracts/secure/{secureLink}', 'ContractsController@show');
    Route::post('/contracts/{id}/approved', 'ContractsController@approve');
    Route::post('/contracts/{id}/decline', 'ContractsController@decline');
    Route::get('/contracts/{id}/review', 'ContractsController@review');
    Route::post('/contracts/{id}', 'ContractsController@store');


    /*
    |--------------------------------------------------------------------------
    | Documents - manage the documents
    |--------------------------------------------------------------------------
    |
    */

    Route::post('documents/application', 'DocumentsController@storeApplicationDocument');
    Route::post('documents/amendment', 'DocumentsController@storeContractAmendmentDocument');
    Route::get('documents/{id}', 'DocumentsController@returnDocument');

    /*
    |--------------------------------------------------------------------------
    | Application form - manage the users application form
    |--------------------------------------------------------------------------
    |
    */
    Route::get('/application-form/new', 'ApplicationController@store_new');
    Route::get('/application-form/{id}/edit', 'ApplicationController@edit');
    Route::get('/application-form/{id}/cancel', 'ApplicationController@cancel');
    Route::post('/step-1/{id}', 'ApplicationController@stepOne');
    Route::post('/step-2/{id}', 'ApplicationController@stepTwo');
    Route::post('/step-3/{id}', 'ApplicationController@stepThree');
    Route::post('/step-4/{id}', 'ApplicationController@stepFour');
    Route::post('/step-5/{id}', 'ApplicationController@stepFive');
    Route::post('/step-6/{id}', 'ApplicationController@stepSix');
    Route::post('/step-7/{id}', 'ApplicationController@stepSeven');
    Route::post('/step-8/{id}', 'ApplicationController@stepEight');
    Route::get('/application-form/{id}', 'ApplicationController@review');
    Route::post('/application-form/{id}/submit', 'ApplicationController@submit');


    /*
    |--------------------------------------------------------------------------
    | Applications - manage the applications
    |--------------------------------------------------------------------------
    |
    */

    Route::get('/application/{id}/review', 'ApplicationProcessController@review');
    Route::get('/application/{id}/approve', 'ApplicationProcessController@approve');
    Route::post('/application/{id}/approve', 'ContractsController@store');
    Route::get('/application/{id}/changes', 'ApplicationProcessController@changesRequested');
    Route::post('/application/{id}/changes', 'ApplicationProcessController@processChangesRequested');
    Route::get('/application/{id}/pending', 'ApplicationProcessController@pending');
    Route::post('/application/{id}/pending', 'ApplicationProcessController@processPending');
    Route::get('/application/{id}/decline', 'ApplicationProcessController@decline');
    Route::post('/application/{id}/decline', 'ApplicationProcessController@processDecline');
    Route::post('/application/{id}/cancel', 'ApplicationProcessController@processCancelApproved');
    Route::post('/application/{id}/renew', 'ApplicationProcessController@renew');

    /*
    |--------------------------------------------------------------------------
    | Support - admin support routes
    |--------------------------------------------------------------------------
    |
    */
    Route::post('/submit-admin-issue', 'SupportController@adminIssue');

});