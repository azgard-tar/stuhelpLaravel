<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\User;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function getImage( Request $request )
    {
        return response()->file( storage_path('app/') . $request->user()->Avatar );
    }

    public function uploadImage( Request $request )
    {
        if( $request->hasFile('Avatar')){
            $request->validate([
                'Avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $user = User::findOrFail(auth()->user()->id);
            if( file_exists( storage_path('app/') . $user->Avatar ) )
                unlink( storage_path('app/') . $user->Avatar );
            $user->Avatar = $request->file('Avatar')->store('images');
            $user->save();
            return response()->file( storage_path('app/') . $user->Avatar );
        }
        return response()->json(['error' => 'Data not found'],404);
    }
}
