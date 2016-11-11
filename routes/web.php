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

Route::get('/', 'HomeController@index');

Route::get('/dashboard', 'DashboardController@index');

Route::get('/profile', 'UserController@profile');

/*
|--------------------------------------------------------------------------
| Users - manage the users of the site
|--------------------------------------------------------------------------
|
*/

Route::get('/users', 'UsersController@index');

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

/*
|--------------------------------------------------------------------------
| Contracts - manage the contracts
|--------------------------------------------------------------------------
|
*/

Route::resource('contracts', 'ContractsController');

/*
|--------------------------------------------------------------------------
| Application form - manage the users application form
|--------------------------------------------------------------------------
|
*/

Route::get('/application-form', 'ApplicationController@create');
Route::post('/application-form', 'ApplicationController@store');
Route::post('/section-2', 'ApplicationController@sectionTwo');
Route::post('/section-3', 'ApplicationController@sectionThree');
Route::post('/section-4', 'ApplicationController@sectionFour');
Route::post('/section-5', 'ApplicationController@sectionFive');
Route::post('/section-6', 'ApplicationController@sectionSix');
Route::post('/section-7', 'ApplicationController@sectionSeven');
Route::post('/section-8', 'ApplicationController@sectionEight');
Route::get('/application-form/{id}', 'ApplicationController@show');