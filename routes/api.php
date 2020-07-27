<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;

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
    Route::get('login', 'AuthController@login')->name('login');
    Route::post('registration', 'AuthController@registration');
    Route::get('logout', 'AuthController@logout');
    Route::get('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
});

Route::group([
    'prefix' => 'user'
], function () {
    Route::put('update', 'UserController@userUpdate');
    Route::get('image', 'ImageController@getImage');
    Route::post('image', 'ImageController@uploadImage');
    Route::delete('delete', 'UserController@delete');
});

Route::group([
    'prefix' => 'admin'
], function () {
    if( auth()->check() && 3 == auth()->user()->Privilege ){
        Route::get('user/{user}', 'AdminController@getOne');
        Route::get('user', 'AdminController@getAll');
        Route::put('update/{user}', 'AdminController@update');
        Route::delete('delete/{user}', 'AdminController@delete');
    }
});

