<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GroupRequests;
use App\Groups;
class GroupRequestsController extends Controller
{
    // get list ( h, m, a )
    public function getList( Request $request ){
        return response()->json( 
            GroupRequests::where(
                'id_Group', $request->user()->id_Group 
            ), 200 
        );
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
    public function applyRequest( Request $request, User $user ){
        $grouprequest = GroupRequests::where('id_User',$user->id )->first();
        if( count( $grouprequest ) !== 0 ){
            $group = Groups::find( $grouprequest->id_Group );
            if( $request->user()->id === $group->id_Headman ){
                $user->id_Group = $group->id;
                $user->save();
                $grouprequest->delete();
                return response()->json( $user , 403);
            }
            return response()->json('Вы не староста этой группы', 403);
        }
        return response()->json('Запросов от этого пользователя нет', 403);
    }
    // delete ( owner, m, a )
    public function deleteRequest( Request $request, GroupRequests $grouprequest ){
        if( $request->user()->id == $grouprequest->id_User 
        || $request->user()->Privilege == 3 
        || $request->user()->Privilege == 4 ){
            $grouprequest->delete();
            return response()->json(null, 203);
        }
        return response()->json('Это не ваш запрос', 203);
    }
}
