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

Route::resource('users', 'Api\UserController');
Route::resource('permissions', 'PermissionsController');
Route::resource('roles', 'RolesController');

Route::get('count/users', 'Api\UserController@count');
Route::get('count/users/active', 'Api\UserController@active');
Route::get('count/users/new', 'Api\UserController@newUsers');
Route::get('count/users/online', 'Api\UserController@online');
Route::get('user/{user}', 'Api\UserController@show');
Route::get('user/delete/{user}', 'Api\UserController@destroy');
Route::post('user/update_role/{user}', 'Api\UserController@updateRole');

