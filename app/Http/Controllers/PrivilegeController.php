<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Privileges;
use App\User;

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
    
    public function setHeadman( Request $request, User $user ){
        $request->validate([
            "id_Group" => "exists:groups,id"
        ]);
        $groupId = $request->id_Group ?? $user->id_Group;
        // искать старосту из группы человека
        if( $groupId && User::where( 'id_Group', $groupId )->where('Privilege',2) )
            return response()->json("У этой группы уже есть староста", 400);
        elseif( ! $groupId )
            return response()->json("Укажите группу( параметр id_Group )", 400);
        $user->id_Group = $groupId;
        $user->Privilege = 2;
        $user->save();
        return response()->json($user,200);
    }
}
