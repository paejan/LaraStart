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

/*
|--------------------------------------------------------------------------
| User/Profile API Routes
|--------------------------------------------------------------------------
|
| Here is where we create/read/update/delete users and assign the
| appropriate user roles. There is also routes to count all users,
| active users, new users, and current online users. Profile routes
| to view/update user profiles is also stored here.
|
*/
Route::get('users/count', 'UserController@count');
Route::get('users/count/active', 'UserController@active');
Route::get('users/count/new', 'UserController@newUsers');
Route::get('users/count/online', 'UserController@online');
Route::resource('users', 'UserController');

Route::post('user/update_role/{user}', 'UserController@updateRole');

Route::get('profile', 'UserController@getProfile');
Route::patch('profile', 'UserController@updateProfile');

/*
|--------------------------------------------------------------------------
| Roles/Permissions Routes
|--------------------------------------------------------------------------
|
| Roles and Permissions are stored into two parts. Permissions allows you
| view a list of all permissions available in the app and Roles
| allows you to to create a new role, view the role with assigned users
| permissions, update the role data (including permisisons), and deletion
| of the role.
|
| Note: Not to be confused with Roles - There is no accessibile way to
| create a new 'Permission' within the web interface. You can do it
| manually by writing `php artisan create:permission` in the console.
|
*/
Route::resource('permissions', 'PermissionsController');
Route::get('roles/users/{id}', 'RolesController@roleUsers');
Route::resource('roles', 'RolesController');
