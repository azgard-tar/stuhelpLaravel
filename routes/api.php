<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::get('login', 'AuthController@login');
    Route::post('registration', 'AuthController@registration');
    Route::get('logout', 'AuthController@logout');
    Route::get('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');

    Route::get('allUsers', 'UserController@getAll');
    Route::get('oneUser/{user}', 'UserController@getOne');
    Route::put('update', 'UserController@userUpdate');
    Route::put('update/{user}', 'UserController@adminUpdate');
    Route::delete('delete/{user}', 'UserController@delete');

    //Route::get('image', 'ImageController@getImage');
});

