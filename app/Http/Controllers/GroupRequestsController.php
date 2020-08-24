<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GroupRequests;
use App\Groups;
use App\User;

class GroupRequestsController extends Controller
{
    // get list ( h, m, a )
    public function getList( Request $request ){
        $group = Groups::find( $request->user()->id_Group );
        if( $group->id_Headman == $request->user()->id )
            return response()->json( 
                GroupRequests::where(
                    'id_Group', $request->user()->id_Group 
                )->get(), 200 
            );
        else
            return response()->json('Вы не староста этой группы', 403);
    }
    // get all ( m , a )
    public function getAll( ){
        return response()->json( GroupRequests::all() , 200 );
    }
    // create ( everyone without group )
    public function createRequest( Request $request, Groups $group ){
        if( $request->user()->id_Group )
            return response()->json("Вы уже состоите в группе",403);
        $temp = GroupRequests::where('id_User', $request->user()->id)->get();
        if( count( $temp ) > 0 )
            return response()->json("Вы уже подали запрос( больше одного нельзя )",403);
        $groupRequest = new GroupRequests;
        $groupRequest->id_User = $request->user()->id;
        $groupRequest->id_Group = $group->id;
        $groupRequest->save();
        return response()->json($groupRequest, 200 );
    }
    // apply request( h )
    public function applyRequest( Request $request, GroupRequests $grouprequest ){
        if( $grouprequest ){
            $group = Groups::find( $grouprequest->id_Group );
            if( $request->user()->id === $group->id_Headman ){
                $user = User::find( $grouprequest->id_User );
                $user->id_Group = $group->id;
                $user->save();
                $grouprequest->delete();
                return response()->json( $user , 200);
            }
            return response()->json('Вы не староста этой группы', 403);
        }
        return response()->json('Этого запроса нет', 403);
    }
    // delete ( owner, m, a )
    public function deleteRequest( Request $request, GroupRequests $grouprequest = null ){
        $req = GroupRequests::where("id_User",auth()->user()->id)->orWhere("id",$grouprequest->id ?? 0)->first();
        if( is_null($req) )
            return response()->json('У вас нет запроса', 404);
        $req->delete();
        return response()->json(null, 203);
    }
}
