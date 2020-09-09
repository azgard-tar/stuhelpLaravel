<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Theme;
use App\User;
use App\Http\Controllers\SubjectController;

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

    static public function isUsersThem( $themeId, User $user = null ){
        $ret = Theme::where( 
            'id_User', ( $user->id ?? auth()->user()->id  ) 
        )->orWhere( 
            'id_Group', ( $user->id_Group ?? ( auth()->user()->id_Group ?? -1 ) ) 
        )->orWhere(
            'global', true
        )->get();
        foreach( $ret as $el )
            if( $el->id == $themeId )
                return true;
        
        return false;
    }

    protected function checkName( Request $request )
    {
        $ret = Theme::where( 
            'id_User', auth()->user()->id 
        )->orWhere( 
            'id_Group', auth()->user()->id_Group ?? -1  
        )->orWhere(
            'global', true
        )->get();
        foreach( $ret as $el )
            if( !is_null( $request->ru_Name )  && mb_strtolower(trim($el->ru_Name )) == mb_strtolower(trim($request->ru_Name ) ) )
                return "ru_Name";
            elseif( !is_null( $request->eng_Name ) && mb_strtolower(trim($el->eng_Name)) == mb_strtolower(trim($request->eng_Name) ) )
                return "eng_Name";
        return "ok";
    }

    // create
    public function addTheme( Request $request )
    {
        $request->validate([
            'id_Subject' => 'exists:subjects,id',
            'global' => 'boolean',
            'withGroup' => 'boolean'
        ]);

        if( ! SubjectController::isUsersSubj( $request->id_Subject ) )
            return response()->json("Это не ваш предмет",404);
        if( $this->checkName($request) !== "ok" )
            return response()->json("У вас уже есть тема с таким названием",400,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);
        
        $theme = new Theme;
        $theme->ru_Name = $request->ru_Name;
        $theme->eng_Name = $request->eng_Name;
        $theme->id_Group = $request->withGroup ? ( auth()->user()->Privilege == 2 ? auth()->user()->id_Group : null ) : null;
        $theme->id_User = auth()->user()->id;
        $theme->id_Subject = $request->id_Subject;
        $theme->global = ( auth()->user()->Privilege == 3 || auth()->user()->Privilege == 4 ) ? $request->global ?? 0  : false;
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

        if( ! SubjectController::isUsersSubj($request->id_Subject) )
            return response()->json("Это не ваш предмет",404);
        //if( $this->checkName($request) !== "ok" )
        //    return response()->json("У вас уже есть тема с таким названием",400,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);

        if( $theme->id_User === auth()->user()->id  ) {
            if( is_null( $request->ru_Name) || $this->checkName($request) === "ru_Name" )
                $theme->update( $request->except(['id_User','id_Group','global','id','ru_Name']) );
            elseif( is_null( $request->eng_Name) || $this->checkName($request) === "eng_Name" )
                $theme->update( $request->except(['id_User','id_Group','global','id','eng_Name']) );
            else
                $theme->update( $request->except(['id_User','id_Group','global','id']) );
            if( auth()->user()->Privilege == 3 || auth()->user()->Privilege == 4 )
                $theme->update( $request->except(['id_User','id_Group','id']) );
            return response()->json( $theme, 200 );
        }
        else 
            return response()->json( "Вы не владелец", 403,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE );   
    }
    // delete

    public function deleteTheme( Request $request, Theme $theme )
    {
        if( $theme->id_User === auth()->user()->id ) {
            $theme->delete();
            return response()->json( null, 203 );
        }
        else 
            return response()->json( "Вы не владелец", 403,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE );   
    }
}
