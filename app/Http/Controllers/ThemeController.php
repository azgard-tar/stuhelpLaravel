<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Theme;

class ThemeController extends Controller
{
    // get user's 
    public function getUserThemes( Request $request, User $user = null)
    {
        return response()->json( 
            [ Theme::where( 
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
    public function addTheme( Request $request )
    {
        $request->validate([
            'id_Subject' => 'exists:subjects,id',
            'global' => 'boolean',
            'withGroup' => 'boolean'
        ]);
        
        $theme = new Theme;
        $theme->ru_Name = $request->ru_Name;
        $theme->eng_Name = $request->eng_Name;
        $theme->id_Group = $request->withGroup ? ( auth()->user()->Privilege == 2 ? auth()->user()->id_Group : null ) : null;
        $theme->id_User = auth()->user()->id;
        $theme->id_Subject = $request->id_Subject;
        $theme->global = ( auth()->user()->Privilege == 3 || auth()->user()->Privilege == 4 ) ? $request->global : false;
        $theme->save();
        return response()->json( Theme::find( $theme->id ) , 200 );
    }
    // update
    public function updateTheme( Request $request, Theme $theme )
    {
        $request->validate([
            'id_Subject' => 'exists:subjects,id',
            'global' => 'boolean'
        ]);

        if( $theme->id_User === auth()->user()->id  ) {
            $theme->update( $request->except(['id_User','id_Group','global','id']) );
            if( auth()->user()->Privilege == 3 || auth()->user()->Privilege == 4 )
                $theme->update( $request->except(['id_User','id_Group','id']) );
            return response()->json( $theme, 200 );
        }
        else 
            return response()->json( "You are not owner of it", 403 );   
    }
    // delete

    public function deleteTheme( Request $request, Theme $theme )
    {
        if( $theme->id_User === auth()->user()->id ) {
            $theme->delete();
            return response()->json( null, 203 );
        }
        else 
            return response()->json( "You are not owner of it", 403 );   
    }
}
