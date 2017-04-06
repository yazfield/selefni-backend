<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::get('/{section}', 'HomeController@index')->where(['section' => '.*']);;
Route::get('/', 'HomeController@index');
// Route::resource('/users', 'UsersController', [
//     'middleware' => 'auth:api'
// ]);
// Route::any('/users/{id}/activate/{code}', 'UsersController@activate')->name('activate_user');

// Route::resource('/items', 'ItemsController'); //->middleware('auth:api');

//Auth::routes();

Route::post('login', 'Auth\LoginController@login');
