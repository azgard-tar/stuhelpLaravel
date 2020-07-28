<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\ImageController;

class AdminController extends Controller
{
    public function getAll( Request $request )
    {
        return response()->json( User::all(),200 );
    }

    public function getOne( Request $request, User $user ){
        return response()->json( $user, 200 );
    }

    public function update( Request $request, User $user ){
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

    public function delete( User $user ){
        $user->delete();
        return response()->json(  null, 204 );
    }

    public function adminGetImage( Request $request, User $user )
    {
        return ImageController::getImage( $request, $user );
    }

    public function adminUploadImage( Request $request, User $user )
    {
        return ImageController::uploadImage( $request, $user );
    }
}
