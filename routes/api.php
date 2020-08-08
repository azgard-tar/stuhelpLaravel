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
    Route::get('login123', 'AuthController@loginTest')->name('login123');
    Route::post('registration', 'AuthController@registration');
    if( auth()->check() ){
        Route::get('logout', 'AuthController@logout');
        Route::get('refresh', 'AuthController@refresh');
        Route::get('me', 'AuthController@me');
    }
});

Route::group([
    'prefix' => 'user'
], function () {
    if( auth()->check() ){
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

        Route::post('discipline','DisciplineController@addDisc'); // C
        Route::get('discipline','DisciplineController@getUserDisc'); // R
        Route::put('discipline/{discipline}','DisciplineController@updateDisc'); // U
        Route::delete('discipline/{discipline}','DisciplineController@deleteDisc'); // D

        Route::post('subject','SubjectController@addSubj'); // C
        Route::get('subject','SubjectController@getUserSubjects'); // R
        Route::put('subject/{subject}','SubjectController@updateSubj'); // U
        Route::delete('subject/{subject}','SubjectController@deleteSubj'); // D

        Route::post('theme','ThemeController@addTheme'); // C
        Route::get('theme','ThemeController@getUserThemes'); // R
        Route::put('theme/{theme}','ThemeController@updateTheme'); // U
        Route::delete('theme/{theme}','ThemeController@deleteTheme'); // D

        Route::get('group', 'GroupController@getGroupStudents');

        Route::post('grouprequests/{group}', 'GroupRequestsController@createRequest');
        Route::delete('grouprequests', 'GroupRequestsController@deleteRequest');

        Route::get('searchgroup','GroupController@searchGroup');

        Route::get('university/{university}', 'UniversityController@getOneUni');
        Route::get('university', 'UniversityController@getAllUni');

        Route::get('country/{country}', 'CountryController@getOneCountry');
        Route::get('country', 'CountryController@getAllCountry');

        Route::get('city/{city}', 'CityController@getOneCity');
        Route::get('city', 'CityController@getAllCity');

        Route::get('group/{group}','GroupController@beautifulGet'); // get info without id_Uni...
        
    }
});

Route::group([
    'prefix' => 'headman'
], function () {
    if( auth()->check() &&   ( 2 <= auth()->user()->Privilege 
    || 4 >= auth()->user()->Privilege ) ){ // headman, moder, admin
        Route::put('group', 'GroupController@updateGroup');

        Route::get('grouprequests', 'GroupRequestsController@getList');
        Route::get('grouprequests/{user}', 'GroupRequestsController@applyRequest');
    }
});

Route::group([
    'prefix' => 'moder'
], function () {
    if( auth()->check() && ( 3 == auth()->user()->Privilege
    || 4 == auth()->user()->Privilege ) ){ // moder, admin
        Route::get('group/{group}', 'GroupController@getGroupStudents');
        Route::get('group', 'GroupController@getGroups');
        Route::post('group', 'GroupController@createGroup');
        Route::put('group/{group}', 'GroupController@updateGroup');
        Route::delete('group/{group}', 'GroupController@deleteGroup');

        Route::get('grouprequests', 'GroupRequestsController@getAll');

        Route::post('university','UniversityController@createUni');
        Route::put('university/{university}','UniversityController@updateUni');
        Route::delete('university/{university}','UniversityController@deleteUni');

        Route::post('country','CountryController@createCountry');
        Route::put('country/{country}','CountryController@updateCountry');
        Route::delete('country/{country}','CountryController@deleteCountry');

        Route::post('city','CityController@createCity');
        Route::put('city/{city}','CityController@updateCity');
        Route::delete('city/{city}','CityController@deleteCity');
    }
});

Route::group([
    'prefix' => 'admin'
], function () {
    if( auth()->check() && 4 == auth()->user()->Privilege ){ // for admins
        Route::get('user/{user}', 'AdminController@getOne');
        Route::get('user', 'AdminController@getAll');
        Route::put('update/{user}', 'AdminController@update');
        Route::delete('delete/{user}', 'AdminController@delete');

        Route::get('image/{user}', 'AdminController@adminGetImage');
        Route::post('image/{user}', 'AdminController@adminUploadImage');

        Route::get('event/{user}','EventController@getUsersEvents');

        Route::get('privilege', 'PrivilegeController@getAllPriv');
        Route::get('privilege/{privilege}', 'PrivilegeController@getPriv');

        Route::get('discipline/{user}','DisciplineController@getUserDisc'); // R
        Route::get('subject/{user}','SubjectController@getUserSubjects'); // R
        Route::get('theme/{user}','ThemeController@getUserThemes'); // R
    }
});

