<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;

class CountryController extends Controller
{
    // get one
    public function getOneCountry( Country $country ){
        return response()->json(Country::find( $country->id), 200 );
    }
    // get all
    public function getAllCountry(){
        return response()->json( Country::all(), 200 );
    }
    // create
    public function createCountry( Request $request ){
        $country = new Country;
        $country->ru_Name = $request->ru_Name;
        $country->eng_Name = $request->eng_Name;
        $country->save();
        return response()->json( $country, 200 );
    }
    // update
    public function updateCountry( Request $request, Country $country ){
        $country->update( $request->all() );
        return response()->json( $country, 200 );
    }
    // delete
    public function deleteCountry( Country $country ){
        $country->delete();
        return response()->json( null, 203 );
    }
}
