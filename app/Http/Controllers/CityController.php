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
    public function getAllCity(){
        return response()->json( City::all(), 200 );
    }
    // create
    public function createCity( Request $request ){
        $city = new City;
        $city->ru_Name = $request->ru_Name;
        $city->eng_Name = $request->eng_Name;
        $city->id_Country = $request->id_Country;
        $city->save();
        return response()->json( $city, 200 );
    }
    // update
    public function updateCity( Request $request, City $city ){
        $city->update( $request->all() );
        return response()->json( $city, 200 );
    }
    // delete
    public function deleteCity( City $city ){
        $city->delete();
        return response()->json( null, 203 );
    }
}
