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
    
    public function getAll( Request $request ){
        if( $request->user()->Privilege > 0 )
            return response()->json( User::all(),200 );
        return response()->json( ['error' => $this->errorPerm ], 403 );
    }
    public function getOne( Request $request, User $user = null ){
        if( $request->user()->Privilege == 0 || is_null( $user ) )
            return response()->json( $request->user(), 200);
        else if( $request->user()->Privilege > 0 && ! is_null( $user )  )
            return response()->json( $user, 200 );
        return response()->json( ['error' => $this->errorPerm], 403 );
    }
    public function userUpdate( Request $request ){

        /*$request->validate([
            'email'    => 'required|email',
            'password' => [
                'required',
                'string',
                'min:10',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ]);

        if( $request->hasFile('Avatar')){
            $request->validate([ 'Avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
            auth()-user()->Avatar = $request->file('Avatar')->store('images');
        }*/
        if( auth()->user()->Privilege <= 0 )
            auth()->user()->update( $request->except(
                ['Privilege','Login','email_verified_at','created_at','updated_at','id_Group','LastLogin','ShopInfo','Coins','Avatar']) 
            );
        else
            auth()->user()->update( $request->except('Avatar') );
        return response()->json( auth()->user(), 200 );
    }
    public function adminUpdate( Request $request, User $user ){
        if( auth()->user()->id == $user->id || auth()->user()->Privilege <= 0 )
            return $this->userUpdate( $request );
        else
            $user->update( $request->all() );
        return response()->json( $user, 200 );
    }
    public function delete( User $user ){
        if( ! is_null(auth()->user()) || auth()->user()->Privilege <= 0 ){
            if(  is_null( $user ) || $user->id == auth()->user()->id ){
                auth()->user()->delete();
                return response()->json( null, 204 );
            }
            return response()->json( ['error' => $this->errorPerm], 403 );
        }
        else if( ! is_null(auth()->user()) || auth()->user()->Privilege > 0 )
            $user->delete();
        else if( is_null(auth()->user()) )
            return response()->json( ['error' => 'Unauthorized'], 401 );
        else
            return response()->json( ['error' => $this->errorPerm], 403 );
    }
}
