<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Groups;

class GroupController extends Controller
{
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
}
