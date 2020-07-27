<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class AdminController extends Controller
{
    public function getAll( Request $request ){

        if( auth()->check() )
            return response()->json( User::all(),200 );
        return response()->json( ['error' => 'Unauthorized' ], 401 );
    }

    public function getOne( Request $request, User $user ){
        if( auth()->check() )
            return response()->json( $user, 200 );
        return response()->json( ['error' => 'Unauthorized' ], 401 );
    }

    public function update( Request $request, User $user ){
        if( auth()->check() ){

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

            $user->update( $request->except('Avatar') );
            return response()->json( $user, 200 );
        }
        return response()->json( ['error' => 'Unauthorized' ], 401 );
    }

    public function delete( User $user ){
        if( auth()->check() ){
            $user->delete();
            return response()->json(  null, 204 );
        }
        else
            return response()->json( ['error' => 'Unauthorized' ], 401 );
    }

}
