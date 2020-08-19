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
            )->orWhere(
                'global', true
            )->get() 
            ], 
            200 
        );
    }
    // create
    public function addDisc( Request $request )
    {
        $request->validate([
            'id_User' => 'exists:users,id',
            'id_Group'=> 'exists:groups,id',
            'global' => 'boolean',
            'withGroup' => 'boolean'
        ]);

        $discipline = new Discipline;
        $discipline->ru_Name = $request->ru_Name;
        $discipline->eng_Name = $request->eng_Name;
        $discipline->id_Group = $request->withGroup ? ( auth()->user()->Privilege == 2 ? auth()->user()->id_Group : null ) : null;
        $discipline->id_User = auth()->user()->id;
        $discipline->global = ( auth()->user()->Privilege == 3 || auth()->user()->Privilege == 4 ) ? $request->global : false;
        $discipline->save();
        return response()->json( Discipline::find( $discipline->id ) , 200 );
    }
    // update
    public function updateDisc( Request $request, Discipline $discipline )
    {        
        $request->validate([
            'global' => 'boolean'
        ]);
        if( $discipline->id_User === auth()->user()->id  ) {
            if( auth()->user()->Privilege < 3 )
                $discipline->update( $request->except(['id_User','id_Group','global','id']) );
            elseif( auth()->user()->Privilege == 3 || auth()->user()->Privilege == 4 )
                $discipline->update( $request->except(['id_User','id_Group','id']) );
            return response()->json( $discipline, 200 );
        }
        else 
            return response()->json( "Вы не владелец", 403 );   
    }
    // delete

    public function deleteDisc( Request $request, Discipline $discipline )
    {
        if( $discipline->id_User === auth()->user()->id ) {
            $discipline->delete();
            return response()->json( null, 203 );
        }
        else 
            return response()->json( "Вы не владелец", 403 );   
    }
}
