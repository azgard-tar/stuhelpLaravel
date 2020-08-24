<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;

class SubjectController extends Controller
{
    // get user's 
    public function getUserSubjects( Request $request, User $user = null)
    {
        return response()->json( 
            [ Subject::where( 
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
    public function addSubj( Request $request )
    {
        $request->validate([
            'id_Discipline' => 'exists:disciplines,id',
            'global' => 'boolean',
            'withGroup' => 'boolean'
        ]);
        $subject = new Subject;
        $subject->ru_Name = $request->ru_Name;
        $subject->eng_Name = $request->eng_Name;
        $subject->id_Group = $request->withGroup ? ( auth()->user()->Privilege == 2 ? auth()->user()->id_Group : null ) : null;
        $subject->id_User = auth()->user()->id;
        $subject->id_Discipline = $request->id_Discipline;
        $subject->global = ( auth()->user()->Privilege == 3 || auth()->user()->Privilege == 4 ) ? $request->global : false;
        $subject->save();
        
        return response()->json( Subject::find( $subject->id ) , 200 );
    }
    // update
    public function updateSubj( Request $request, Subject $subject )
    {
        $request->validate([
            'id_Discipline' => 'exists:disciplines,id',
            'global' => 'boolean'
        ]);
        
        if( $subject->id_User === auth()->user()->id  ) {
            $subject->update( $request->except(['id_User','id_Group','global','id']) );
            if( auth()->user()->Privilege == 3 || auth()->user()->Privilege == 4 )
                $theme->update( $request->except(['id_User','id_Group','id']) );
            return response()->json( $subject, 200 );
        }
        else 
            return response()->json( "You are not owner of it", 403 );   
    }
    // delete

    public function deleteSubj( Request $request, Subject $subject )
    {
        if( $subject->id_User === auth()->user()->id ) {
            $subject->delete();
            return response()->json( null, 203 );
        }
        else 
            return response()->json( "You are not owner of it", 403 );   
    }
}
