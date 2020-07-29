<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Privileges;

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
}
