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
        $authPriv = auth()->user()->Privilege;
        if( 
            ( $authPriv == 3 || $authPriv == 4 ) && /* Если это админ/модер */
            ( $user->id == auth()->user()->id || /* И он пытается стать старостой */
              $user->Privilege == 3 || $user->Privilege == 4 ) /* Или сделать старостой другого админа/модера */
        )
                return response()->json("Модер или админ не может быть старостой",400); // то нельзя

        $request->validate([
            "id_Group" => "exists:groups,id"
        ]);
        $groupId = $request->id_Group ?? $user->id_Group;
        // искать старосту из группы человека
        if( $groupId && User::where( 'id_Group', $groupId )->where('Privilege',2) )
            if( auth()->user()->Privilege == 4 )
                User::where( 'id_Group', $groupId )->where('Privilege',2)->update(["Privilege" => 1]);
            else
                return response()->json("У этой группы уже есть староста", 400);
        elseif( ! $groupId )
            return response()->json("Укажите группу( параметр id_Group )", 400);
        
        $group = Groups::find( $groupId );
        $group->id_Headman = $user->id;
        $group->save();
        
        $user->id_Group = $groupId;
        $user->Privilege = 2;
        $user->save();
        
        return response()->json($user,200);
    }
}
