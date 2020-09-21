<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Groups;
use App\User;
use App\University;
use App\Http\Controllers\PrivilegeController;
class GroupController extends Controller
{
    // beautifull response - without id
    public function beautifulGet( Request $request, Groups $Rgroup = null ){
        $request->validate([
            'id_University' => 'exists:universities,id'
        ]);
        $group = Groups::find( $Rgroup->id ?? auth()->user()->id_Group );
        if( $group ){
            $headm = User::find( $group->id_Headman );
            $uni = University::find( $group->id_University );
            $group->university = $uni->ru_Name ?? null;
            $group->headman = $headm->Login;
            unset( $group->id_University, $group->id_Headman );
            return response()->json( $group, 200 );
        }
        return response()->json(["error" => "У вас нет группы"],403,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);
    }
    // search group 
    public function searchGroup( Request $request ){
        $request->validate([
            'id_University' => 'exists:universities,id'
        ]);
        if( $request->Name && $request->id_University ){
            return response()->json(
                Groups::where( 'id_University', $request->id_University
                     )->where( 'Name',          $request->Name )->get()      
                ,200
            );
        } else if( $request->Name || $request->id_University ) {
            return response()->json([
                Groups::where( 'id_University', $request->id_University
                     )->orWhere( 'Name',          $request->Name )->get()]     
                ,200
            );
        } else{
            return response()->json("Нет данных",403,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);
        }
    }
    // get students
    public function getGroupStudents( Request $request, Groups $group = null ){
        $currentId = ( is_null( $group ) ) ? auth()->user()->id_Group : $group->id;
        if( $currentId )
            return response()->json( [
                        "Students" => User::where(
                            'id_Group',
                            $currentId 
                        )->get(["id","Login","name","Surname","email"]),
                        "Info" => Groups::find( $currentId  )                        
                ], 200 
            );
        return response()->json(["error" => "У вас нет группы"],403,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);
    }
    // get group
    public function getGroups(){
        return response()->json( Groups::all(), 200 );
    }
    public function getOneGroup( Groups $group){
        return response()->json( Groups::find( $group->id ), 200 );
    }

    // create
    public function createGroup( Request $request ){
        $request->validate([
            'id_Headman' => 'exists:users,id',
            'id_University' => 'exists:universities,id'
        ]);

        $user = User::find($request->id_Headman);
        if( !is_null( $user->id_Group ) ){
            return response()->json("Указанный юзер уже состоит в группе",400,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);
        }
        $group = new Groups;
        $group->Name = $request->Name;
        $group->id_University = $request->id_University;
        $group->id_Headman = $request->id_Headman;
        $group->save();
        
        $user->id_Group = $group->id;
        $user->Privilege = 2;
        $user->save();

        return response()->json( Groups::find($group->id),200);
    }
    // update
    public function updateGroup( Request $request, Groups $group ){
        $request->validate([
            'id_Headman' => 'exists:users,id',
            'id_University' => 'exists:universities,id'
        ]);
        if( !is_null( $request->id_Headman ) ){
            $authPriv = auth()->user()->Privilege;
            $user = User::find( $request->id_Headman );
            if( 
                ( $authPriv == 3 || $authPriv == 4 ) && /* Если это админ/модер */
                ( $user->id == auth()->user()->id || /* И он пытается стать старостой */
                $user->Privilege == 3 || $user->Privilege == 4 ) /* Или сделать старостой другого админа/модера */
            )
                return response()->json("Модер или админ не может быть старостой",400,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE); // то нельзя

            User::where( 'id_Group', $group->id )->where('Privilege',2)->update(["Privilege" => 1]);
            
            $user->id_Group = $group->id;
            $user->Privilege = 2;
            $user->save();
        }
        $group->update($request->except('id'));            
        return response()->json( $group, 200 );
    }
    // delete 
    public function deleteGroup( Request $request, Groups $group ){
        $group->delete();
        return response()->json( null, 203 );
    }

    public function leaveFromGroup( ){
        auth()->user()->id_Group = null;
        return response()->json(auth()->user(),200);
    }
}
