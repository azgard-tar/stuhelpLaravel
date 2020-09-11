<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Privileges;
use App\User;
use App\Groups;

class PrivilegeController extends Controller
{
    // get one
    public function getPriv( Request $request, Privileges $privilege )
    {
        return response()->json( 
            Privileges::where( 
                'id', $privilege->id ?? $request->user()->Privilege 
            )->first()
        , 200 );
    }
    // get all
    public function getAllPriv( )
    {
        return response()->json( 
            Privileges::all()
        , 200 );
    }

    public function setModer( Request $request, User $user ){
        if( $user->id == auth()->user()->id || $user->Privilege == 4 )
            return response()->json("Не нужно снижать права админов!",400 );
        if( $user->Privilege == 2 )
            return response()->json("Староста не может быть модером. Смените старосту группы",400 );

        $user->Privilege = 3;
        $user->save();
        
        return response()->json($user,200);
    }
}
