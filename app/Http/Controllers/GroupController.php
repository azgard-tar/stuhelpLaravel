<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Groups;

class GroupController extends Controller
{
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
        return response()->json("You don't have a group",403);
    }
    // get group
    public function getGroups(){
        return response()->json( Groups::all(), 200 );
    }

    // create
    public function createGroup( Request $request ){
        $request->validate([
            'id_Headman' => 'exists:users,id'
        ]);

        $group = new Groups;
        $group->Name = $request->Name;
        $group->id_University = $request->id_University;
        $group->id_Headman = $request->id_Headman;
        $group->save();
        return response()->json( Groups::find($group->id),200);
    }
    // update
    public function updateGroup( Request $request, Groups $group = null ){
        $request->validate([
            'id_Headman' => 'exists:users,id'
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
}
