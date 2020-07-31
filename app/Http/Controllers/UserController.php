<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\ImageController;

class UserController extends Controller
{
    private $errorPerm = "Permission denied";

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function userUpdate( Request $request ){
        $request->validate([
            'email'    => 'email|unique:users',
            'password' => [
                'string',
                'min:10',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'id_Group' => 'exists:groups,id',
            'id_City' => 'exists:cities,id',
            'id_Country' => 'exists:countries,id'
        ]);
        auth()->user()->update( $request->except(
            ['Privilege','Login','email_verified_at','created_at','updated_at','LastLogin','Avatar']) 
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
