<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AboutEvent;
use App\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventController extends Controller
{

    public function getOne( Request $request, AboutEvent $event )
    {
        return ( $event->id_User === $user->id || 
        !is_null( $event->id_Group ) && $event->id_Group === $user->id_Group  
            ) ? 
            response()->json( $event, 200 ) : 
            response()->json( "This event is not for you", 403 );   
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

        $validator = Validator::make($request->all(), [
            'EvWhen' => 'date|after_or_equal:today',
            'EvWhenEnd' => 'date|after:EvWhen',
            'WhenDoHW' => 'date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),403);
        }

        AboutEvent::create([
            'Name'          => $request->Name,
            'Description'   => $request->Description,
            'EvWhen'        => $request->EvWhen,
            'EvWhenEnd'     => $request->EvWhenEnd,
            'id_User'       => auth()->user()->id ,
            'EvType'        => $request->EvType ?? 0,
            'EvWhere'       => $request->EvWhere,
            'id_Subject'    => $request->id_Subject,
            'id_Theme'      => $request->id_Theme,
            'Keywords'      => $request->Keywords,
            'Questions'     => $request->Questions,
            'Homework'      => $request->Homework,
            'WhenDoHW'      => $request->WhenDoHW,
            'Color'         => $request->Color, 
            'id_Group'      => ( auth()->user()->Privilege == 1 ? $request->id_Group : null )
        ]);
        return response()->json( 
            AboutEvent::where('Name',$request->name
                )->where(
                    'id_User', auth()->user()->id
                )->get()
            , 200 );
    }
    // update
    public function updateEvent( Request $request, AboutEvent $event )
    {
        $validator = \Validator::make($request->all(), [
            'EvWhen' => 'date|after_or_equal:today',
            'EvWhenEnd' => 'date|after:EvWhen',
            'WhenDoHW' => 'date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),403);
        }

        if( $event->id_User === auth()->user()->id  ) {
            $event->update( $request->except('id_User','id_Group') );
            return response()->json( $event, 200 );
        }
        else 
            return response()->json( "You are not owner of this event", 403 );   
    }
    // delete
    public function deleteEvent( Request $request, AboutEvent $event )
    {
        if( $event->id_User === auth()->user()->id ) {
            $event->delete();
            return response()->json( null, 203 );
        }
        else 
            return response()->json( "You are not owner of this event", 403 );   
    }
}
