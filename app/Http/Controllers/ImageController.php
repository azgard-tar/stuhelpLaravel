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

    public static function getImage( Request $request, User $user = null )
    {
        return response()->file( storage_path('app/') . ( ( is_null($user) ) ? $request->user()->Avatar : $user->Avatar ) );
    }

    public static function uploadImage( Request $request, User $otherUser = null ){
        $user = User::findOrFail( ( is_null($otherUser) ) ? auth()->user()->id : $otherUser->id );
        if( $request->hasFile('Avatar')){
            $request->validate([
                'Avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if( file_exists( storage_path('app/') . $user->Avatar ) && $user->Avatar !== 'images/none.jpg' )
                unlink( storage_path('app/') . $user->Avatar );
            $user->Avatar = $request->file('Avatar')->store('images');
            $user->save();
            return response()->file( storage_path('app/') . $user->Avatar );
        }
        return response()->json(['error' => 'Data not found'],404);
    }
}
