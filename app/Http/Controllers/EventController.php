<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AboutEvent;
use App\User;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ThemeController;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventController extends Controller
{

    public function getOne( Request $request, AboutEvent $event )
    {
        return ( $event->id_User === auth()->user()->id || 
        !is_null( $event->id_Group ) && $event->id_Group === auth()->user()->id_Group  
            ) ? 
            response()->json( $event, 200 ) : 
            response()->json( "Это не ваше событие", 403,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE );   
    }

    public function getUsersEvents( Request $request, User $user = null)
    {
        return response()->json( 
            [ AboutEvent::where( 
                'id_User', ( $user->id ?? auth()->user()->id  ) 
            )->orWhere( 
                'id_Group', ( $user->id_Group ?? ( auth()->user()->id_Group ?? -1 ) ) 
            )->get() 
            ], 
            200 
        );
    }
    // create 
    public function addEvent( Request $request )
    {

        $request->validate([
            'EvWhen' => 'date|after_or_equal:today',
            'EvWhenEnd' => 'date|after_or_equal:today',
            'WhenDoHW' => 'date|after_or_equal:today',
            'id_Subject' => 'exists:subjects,id',
            'id_Theme' => 'exists:themes,id',
            'withGroup' => 'boolean'
        ]);

        if( $request->id_Subject && ! SubjectController::isUsersSubj($request->id_Subject) )
            return response()->json("Это не ваш предмет",404,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);
        elseif( $request->id_Theme && ! ThemeController::isUsersThem($request->id_Theme) )
            return response()->json("Это не ваша тема",404,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);

        
        // type == 0 - личный ивент
        // type == 1 - пара в универе

        AboutEvent::create([
            'Name'          => $request->Name,
            'Description'   => $request->Description,
            'EvWhen'        => $request->EvWhen,
            'EvWhenEnd'     => $request->EvWhenEnd,
            'id_User'       => auth()->user()->id ,
            'EvType'        => $request->EvType ?? 0,
            'EvWhere'       => $request->EvWhere,
            'id_Subject'    => $request->EvType == 1 ? $request->id_Subject : null,
            'id_Theme'      => $request->EvType == 1 ? $request->id_Theme   : null,
            'Keywords'      => $request->EvType == 1 ? $request->Keywords   : null,
            'Questions'     => $request->EvType == 1 ? $request->Questions  : null,
            'Homework'      => $request->EvType == 1 ? $request->Homework   : null,
            'WhenDoHW'      => $request->EvType == 1 ? $request->WhenDoHW   : null,
            'Color'         => $request->Color, 
            'id_Group'      => $request->withGroup ? ( auth()->user()->Privilege == 2 ? auth()->user()->id_Group : null ) : null
        ]);
        return response()->json( 
            AboutEvent::where('Name',$request->Name
                )->where(
                    'id_User', auth()->user()->id
                )->get()
            , 200 );
    }
    // update
    public function updateEvent( Request $request, AboutEvent $event )
    {
        $request->validate([
            'EvWhen' => 'date|after_or_equal:today',
            'EvWhenEnd' => 'date|after:EvWhen',
            'WhenDoHW' => 'date|after_or_equal:today',
            'id_Subject' => 'exists:subjects,id',
            'id_Theme' => 'exists:themes,id'
        ]);

        if( $request->id_Subject && ! SubjectController::isUsersSubj($request->id_Subject) )
            return response()->json("Это не ваш предмет",404,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);
        elseif( $request->id_Theme && ! ThemeController::isUsersThem($request->id_Theme) )
            return response()->json("Это не ваша тема",404,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);

        if( $event->id_User === auth()->user()->id  ) {
            $event->update( $request->except('id_User','id_Group','id') );
            return response()->json( $event, 200 );
        }
        else 
            return response()->json( ["error" => "Это не ваше событие"], 
            403,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE );   
    }
    // delete
    public function deleteEvent( Request $request, AboutEvent $event )
    {
        if( $event->id_User === auth()->user()->id ) {
            $event->delete();
            return response()->json( null, 204 );
        }
        else 
            return response()->json( ["error" => "Это не ваше событие"], 403 ,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);   
    }
}
