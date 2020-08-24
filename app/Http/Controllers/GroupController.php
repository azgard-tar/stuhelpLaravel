<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Groups;
use App\User;
use App\University;
class GroupController extends Controller
{
    // beautifull response - without id
    public function beautifulGet( Request $request, Groups $Rgroup = null ){
        $request->validate([
            'id_University' => 'exists:universities,id'
        ]);
        $group = Groups::find( $Rgroup->id ?? auth()->user()->id_Group );
        $headm = User::find( $group->id_Headman );
        $uni = University::find( $group->id_University );
        $group->university = $uni->ru_Name ?? null;
        $group->headman = $headm->Login;
        unset( $group->id_University, $group->id_Headman );
        return response()->json( $group, 200 );
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
            return response()->json("Нет данных",403);
        }
    }
    // get students
    public function getGroupStudents( Request $request, Groups $group = null ){
        if( auth()->user()->id_Group || $group->id )
            return response()->json( [
                        "Students" => Groups::where(
                            'id',
                            $group->id ?? auth()-user()->id_Group 
                        )->select(
                            'name','Surname', 'Login', 'email', 'id_Group', 'Avatar'
                        ),
                        "Info" => Groups::find( $group->id ?? auth()-user()->id_Group )                        
                ], 200 
            );
        return response()->json("У вас нет группы",403);
    }
    // get group
    public function getGroups(){
        return response()->json( Groups::all(), 200 );
    }

    // create
    public function createGroup( Request $request ){
        $request->validate([
            'id_Headman' => 'exists:users,id',
            'id_University' => 'exists:universities,id'
        ]);

        $user = User::find($request->id_Headman);
        if( is_null( $user->id_Group ) ){
            return response()->json("Указанный юзер уже состоит в группе",400);
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
    public function updateGroup( Request $request, Groups $group = null ){
        $request->validate([
            'id_Headman' => 'exists:users,id',
            'id_University' => 'exists:universities,id'
        ]);
        $currentGroup = Groups::find( $group->id ?? auth()->user()->id_Group );
        $currentGroup->update($request->except('id'));            
        return response()->json( $currentGroup, 200 );
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
