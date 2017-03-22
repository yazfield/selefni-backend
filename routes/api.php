<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('/users', 'UsersController'); //->middleware('auth:api');
Route::any('/users/{id}/activate/{code}', 'UsersController@activate')->name('activate_user');

Route::resource('/items', 'ItemsController'); //->middleware('auth:api');
