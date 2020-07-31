<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\University;

class UniversityController extends Controller
{
    // get one
    public function getOneUni( University $university ){
        return response()->json(University::find( $university->id), 200 );
    }
    // get all
    public function getAllUni(){
        return response()->json( University::all(), 200 );
    }
    // create ( moder, admin )
    public function createUni( Request $request ){
        $request->validate([
            'id_City' => 'exists:cities,id',
            'id_Country' => 'exists:countries,id'
        ]);
        $university = new University;
        $university->ru_Name = $request->ru_Name;
        $university->eng_Name = $request->eng_Name;
        $university->id_City = $request->id_City;
        $university->id_Country = $request->id_Country;
        $university->save();
        return response()->json( $university, 200 );
    }
    // update ( moder, admin )
    public function updateUni( Request $request, University $university ){
        $request->validate([
            'id_City' => 'exists:cities,id',
            'id_Country' => 'exists:countries,id'
        ]);
        $university->update( $request->all() );
        return response()->json( $university, 200 );
    }
    // delete ( moder, admin )
    public function deleteUni( University $university ){
        $university->delete();
        return response()->json( null, 203 );
    }
}
