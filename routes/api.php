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
    Route::delete('delete', 'UserController@delete');

    Route::get('image', 'UserController@userGetImage');
    Route::post('image', 'UserController@userUploadImage');
    
    Route::post('event','EventController@addEvent'); // C
    Route::get('event/{event}','EventController@getOne'); // R
    Route::get('event','EventController@getUsersEvents');
    Route::put('event/{event}','EventController@updateEvent'); // U
    Route::delete('event/{event}','EventController@deleteEvent'); // D

    Route::get('privilege', 'PrivilegeController@getPriv');
});

Route::group([
    'prefix' => 'admin'
], function () {
    if( auth()->check() && 4 == auth()->user()->Privilege ){
        Route::get('user/{user}', 'AdminController@getOne');
        Route::get('user', 'AdminController@getAll');
        Route::put('update/{user}', 'AdminController@update');
        Route::delete('delete/{user}', 'AdminController@delete');

        Route::get('image/{user}', 'AdminController@adminGetImage');
        Route::post('image/{user}', 'AdminController@adminUploadImage');

        Route::get('event/{user}','EventController@getUsersEvents');

        Route::get('privilege', 'PrivilegeController@getAllPriv');
        Route::get('privilege/{privilege}', 'PrivilegeController@getPriv');
    }
});

