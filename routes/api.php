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

Route::resource('users', 'UserController');
Route::resource('permissions', 'PermissionsController');
Route::resource('roles', 'RolesController');

Route::get('count/users', 'UserController@count');
Route::get('count/users/active', 'UserController@active');
Route::get('count/users/new', 'UserController@newUsers');
Route::get('count/users/online', 'UserController@online');
Route::get('user/{user}', 'UserController@show');
Route::get('user/delete/{user}', 'UserController@destroy');
Route::post('user/update_role/{user}', 'UserController@updateRole');
Route::get('profile', 'UserController@showProfile');

Route::get('roles/users/{id}', 'RolesController@roleUsers');
