<?php

use Illuminate\Http\Request;

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


Route::post('auth/register', 'Api\AuthController@register');
Route::post('auth/login', 'Api\AuthController@login');
Route::post('auth/forgotten-password', 'Api\AuthController@forgottenPassword');
Route::post('auth/reset-password', 'Api\AuthController@resetPassword');
Route::get('auth/check-token-validity/{token}', 'Api\AuthController@checkTokenValidity');

Route::group(['middleware' => 'jwt.auth'], function(){
    //User
    Route::get('auth/user', 'Api\AuthController@user');
    Route::post('auth/logout', 'Api\AuthController@logout');
    Route::post('auth/user/{id}/update', 'Api\UserController@update');
    Route::post('auth/user/{id}/update/profile-picture', 'Api\UserController@updateProfilePicture');
    //Todo List
    Route::post('auth/todo/list/create', 'Api\TodoListController@store');
});
Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('auth/refresh', 'Api\AuthController@refresh');
});
