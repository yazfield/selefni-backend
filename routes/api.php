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

Route::group(['prefix' => '/users',], function ()
{
    Route::get('/searchFriends', 'UsersController@searchFriends')->middleware('auth:api');
    Route::get('/{id}', 'UsersController@show')->middleware('auth:api');
    Route::post('/', 'UsersController@store');
    Route::post('/{id}/avatar', 'UsersController@setAvatar')
         ->middleware('auth:api');
    Route::put('/{id}', 'UsersController@update')->middleware('auth:api');
    Route::delete('/{id}', 'UsersController@destroy')->middleware('auth:api');
});
 //->middleware('auth:api');
Route::any('/users/{id}/activate/{code}', 'UsersController@activate')->name('activate_user');

Route::get('/profile', 'UsersController@profile')->middleware('auth:api');

Route::post('/logout', 'UsersController@logout')->middleware('auth:api');

//Route::resource('/items', 'ItemsController');//->middleware('auth:api');
Route::put('/items/{id}', 'ItemsController@update');
Route::post('/items/{id}/media', 'ItemsController@setImage');
Route::get('/user_items', 'ItemsController@user')->middleware('auth:api');
