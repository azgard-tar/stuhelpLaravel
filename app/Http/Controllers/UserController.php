<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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
            ]
        ]);
        if( auth()->user()->Privilege <= 0 )
            auth()->user()->update( $request->except(
                ['Privilege','Login','email_verified_at','created_at','updated_at','id_Group','LastLogin','ShopInfo','Coins','Avatar']) 
            );
        else
            auth()->user()->update( $request->except('Avatar') );
        return response()->json( auth()->user(), 200 );
    }
    
    public function delete( Request $request ){
            if( auth()->check() ){
                $request->delete();
                return response()->json( null, 204 );
            }
            return response()->json( ['error' => 'Unauthorized'], 401 );
    }
}
