<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discipline;

class DisciplineController extends Controller
{
    // get user's 
    public function getUserDisc( Request $request, User $user = null)
    {
        return response()->json( 
            [ Discipline::where( 
                'id_User', ( $user->id ?? auth()->user()->id  ) 
            )->orWhere( 
                'id_Group', ( $user->id_Group ?? ( auth()->user()->id_Group ?? -1 ) ) 
            )->get() 
            ], 
            200 
        );
    }
    // create
    public function addDisc( Request $request )
    {
        $request->validate([
            'id_User' => 'exists:users,id'
        ]);

        $discipline = new Discipline;
        $discipline->ru_Name = $request->ru_Name;
        $discipline->eng_Name = $request->eng_Name;
        $discipline->id_Group = (auth()->user()->Privilege == 2 
            && auth()->user()->id_Group == $request->id_Group) 
            ? auth()->user()->id_Group : null;
        $discipline->id_User = auth()->user()->id;
        $discipline->save();
        return response()->json( Discipline::find( $discipline->id ) , 200 );
    }
    // update
    public function updateDisc( Request $request, Discipline $discipline )
    {
        $request->validate([
            'id_User' => 'exists:users,id'
        ]);
        
        if( $discipline->id_User === auth()->user()->id  ) {
            $discipline->update( $request->except('id_User','id_Group') );
            return response()->json( $discipline, 200 );
        }
        else 
            return response()->json( "You are not owner of it", 403 );   
    }
    // delete

    public function deleteDisc( Request $request, Discipline $discipline )
    {
        if( $discipline->id_User === auth()->user()->id ) {
            $discipline->delete();
            return response()->json( null, 203 );
        }
        else 
            return response()->json( "You are not owner of it", 403 );   
    }
}
