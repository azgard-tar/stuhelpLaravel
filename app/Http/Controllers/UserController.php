<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\City;
use App\Country;
use App\Http\Controllers\ImageController;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    

    public function userUpdate( Request $request ){
        $request->validate([
            'email'    => 'email|unique:users',
            'password' => [
                'string',
                'min:6',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
            'id_City' => 'exists:cities,id',
            'id_Country' => 'exists:countries,id'
        ]);

        
        $city = City::find( $request->id_City ?? auth()->user()->id_City );
        $country = Country::find( $request->id_Country ?? auth()->user()->id_Country );
        if( !is_null( $city ) && !is_null( $country ) && $city->id_Country != $country->id )
            return response()->json(["error" => $city->ru_Name . " не из этой страны( " . $country->ru_Name . " )"],400);

        if( !is_null($request->Avatar) ){
            $this->userUploadImage( $request );
        }
        auth()->user()->update( $request->except(
            ['Privilege','Login','email_verified_at','created_at','updated_at','LastLogin','Avatar','id_Group','id']) 
        );

        return response()->json( auth()->user(), 200 );
    }
    
    public function delete( Request $request ){
        auth()->user()->delete();
        return response()->json( null, 204 );
    }

    public function userGetImage( Request $request )
    {
        return ImageController::getImage( $request );
    }
    
    public function userUploadImage( Request $request )
    {
        return ImageController::uploadImage( $request );
    }
}
