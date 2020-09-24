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

    public static function uploadImage( Request $request, User $user = null ){
        $currentUser = User::findOrFail( ( is_null($user) ) ? auth()->user()->id : $user->id );
        if( $request->hasFile('Avatar')){
            $request->validate([
                'Avatar' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
            ],[
                "mimes" => "Расширение файла не поддерживается",
                "max"   => "Размер файла слишком большой",
                "image" => "Файл должен быть картинкой"
            ]);
            if( file_exists( storage_path('app/') . $currentUser->Avatar ) && $currentUser->Avatar !== 'images/none.jpg' )
                unlink( storage_path('app/') . $currentUser->Avatar );
            $currentUser->Avatar = $request->file('Avatar')->store('images');
            $currentUser->save();
            return response()->file( storage_path('app/') . $currentUser->Avatar );
        }
        return response()->json(['error' => 'Картинка не найдена'],404);
    }
}
