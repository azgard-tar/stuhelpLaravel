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

    static public function isUsersDisc( $disciplineId, User $user = null ){
        $ret = Discipline::where( 
            'id_User', ( $user->id ?? auth()->user()->id  ) 
        )->orWhere( 
            'id_Group', ( $user->id_Group ?? ( auth()->user()->id_Group ?? -1 ) ) 
        )->orWhere(
            'global', true
        )->get();
        foreach( $ret as $el )
            if( $el->id == $disciplineId )
                return true;
        
        return false;
    }

    protected function checkName( Request $request )
    {
        $ret = Discipline::where( 
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
    public function addDisc( Request $request )
    {
        $rules = [
            'ru_Name'  => "required|min:5",
            'eng_Name' => 'min:5',
            'global' => 'boolean',
            'withGroup' => 'boolean'
        ];
        $messages = [
            "ru_Name.required" => "Укажите название дисциплины",
            "ru_Name.min" => "Минимальная длина названия составляет 5 символов",
            "eng_Name.min" => "Минимальная длина описания составляет 5 символов"
        ];

        $validation = \Validator::make( $request->all(), $rules, $messages );

        if($validation->fails()) {
            return response()->json([ "error" => $validation->messages()->first() ], 401);
        }

        if( $this->checkName($request) !== "ok" )
            return response()->json(["error" => "У вас уже есть дисциплина с таким названием" ],400,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);

        $discipline = new Discipline;
        $discipline->ru_Name = $request->ru_Name;
        $discipline->eng_Name = $request->eng_Name;
        $discipline->id_Group = $request->withGroup ? ( auth()->user()->Privilege == 2 ? auth()->user()->id_Group : null ) : null;
        $discipline->id_User = auth()->user()->id;
        $discipline->global = ( auth()->user()->Privilege == 3 || auth()->user()->Privilege == 4 ) ? $request->global ?? 0 : false;
        $discipline->save();
        return response()->json( Discipline::find( $discipline->id ) , 200 );
    }
    // update
    public function updateDisc( Request $request, Discipline $discipline )
    {        
        $rules = [
            'ru_Name'  => 'min:5',
            'eng_Name' => 'min:5',
            'global' => 'boolean',
            'withGroup' => 'boolean'
        ];
        $messages = [
            "ru_Name.min" => "Минимальная длина названия составляет 5 символов",
            "eng_Name.min" => "Минимальная длина описания составляет 5 символов"
        ];

        $validation = \Validator::make( $request->all(), $rules, $messages );

        if($validation->fails()) {
            return response()->json([ "error" => $validation->messages()->first() ], 401);
        }

        if( $discipline->id_User === auth()->user()->id  ) {
            if( auth()->user()->Privilege < 3 )
                if( is_null( $request->ru_Name) || $this->checkName($request) === "ru_Name" )
                    $discipline->update( $request->except(['id_User','id_Group','global','id','ru_Name']) );
                elseif( is_null( $request->eng_Name) || $this->checkName($request) === "eng_Name" )
                    $discipline->update( $request->except(['id_User','id_Group','global','id','eng_Name']) );
                else
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
            return response()->json( "Вы не владелец", 403, ["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE );   
    }
}
