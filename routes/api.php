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

Route::get('users/count', 'UserController@count');
Route::get('users/count/active', 'UserController@active');
Route::get('users/count/new', 'UserController@newUsers');
Route::get('users/count/online', 'UserController@online');
Route::resource('users', 'UserController');

Route::resource('permissions', 'PermissionsController');
Route::resource('roles', 'RolesController');

Route::get('count/users/active', 'UserController@active');
Route::get('count/users/new', 'UserController@newUsers');
Route::get('count/users/online', 'UserController@online');
Route::post('user/update_role/{user}', 'UserController@updateRole');

Route::get('profile', 'UserController@getProfile');
Route::patch('profile', 'UserController@updateProfile');

Route::get('roles/users/{id}', 'RolesController@roleUsers');
Route::get('roles/delete/{role}', 'RolesController@destroy');
