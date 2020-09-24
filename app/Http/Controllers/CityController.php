<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;

class CityController extends Controller
{
    // get one
    public function getOneCity( City $city ){
        return response()->json(City::find( $city->id), 200 );
    }
    // get all
    public function getAllCity( Request $request ){
        if( is_null($request->id_Country) )
            return response()->json( City::all(), 200 );
        else{
            $request->validate([
                "id_Country" => "exists:countries,id"
            ],[
                "id_Country.exists" => "Выберите существующую страну"
            ]);
            return response()->json( City::where( 'id_Country', $request->id_Country )->get() ,200 );
        }
    }
    // create
    public function createCity( Request $request ){
        $request->validate([
            'id_Country' => 'exists:countries,id'
        ]);
        $city = new City;
        $city->ru_Name = $request->ru_Name;
        $city->eng_Name = $request->eng_Name;
        $city->id_Country = $request->id_Country;
        $city->save();
        return response()->json( $city, 200 );
    }
    // update
    public function updateCity( Request $request, City $city ){
        $request->validate([
            'id_Country' => 'exists:countries,id'
        ]);
        $city->update( $request->except('id') );
        return response()->json( $city, 200 );
    }
    // delete
    public function deleteCity( City $city ){
        $city->delete();
        return response()->json( null, 203 );
    }
}
