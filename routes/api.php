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
Route::post('auth/reset-password/{token}', 'Api\AuthController@resetPassword');

Route::group(['middleware' => 'jwt.auth'], function(){
    Route::get('auth/user', 'Api\AuthController@user');
    Route::post('auth/logout', 'Api\AuthController@logout');
});
Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('auth/refresh', 'Api\AuthController@refresh');
});
