<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function getImage( Request $request )
    {
        if( auth()->user()->Privilege <= 0 )
            return Storage::get(public_path('images') . '/' . auth()->user()->Avatar);
        else
            return Storage::get(public_path('images') . '/' . $request->user()->Avatar);
    }
}
