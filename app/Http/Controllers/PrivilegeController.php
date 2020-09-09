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

        User::where( 'id_Group', $groupId )->where('Privilege',2)->update(["Privilege" => 1]);
        
        $group = Groups::find( $groupId );
        $group->id_Headman = $user->id;
        $group->save();
        
        $user->id_Group = $groupId;
        $user->Privilege = 2;
        $user->save();
        
        return response()->json($user,200);
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
