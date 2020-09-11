<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\ImageController;
use Illuminate\Database\Eloquent\Collection;

class AdminController extends Controller
{
    public function getAll( Request $request )
    {
        return response()->json( User::all(),200 );
    }

    public function getOne( Request $request, User $user ){
        return response()->json( $user, 200 );
    }

    public function update( Request $request, User $user ){
        $request->validate([
            'email'    => 'email|unique:users',
            'password' => [
                'string',
                'min:6',             // must be at least 6 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ]
        ]);

        $user->update( $request->except('Avatar') );
        return response()->json( $user, 200 );
    }

    public function searchUser( Request $request ){
        $request->validate(
            [
                'name' => 'string',
                'Surname' => 'string',
                'Login' => 'string',
                'id_City' => 'exists:cities,id|integer',
                'id_Country' => 'exists:countries,id|integer',
                'Privilege' => 'integer'
            ]
        );

        if( $request->name )
            return response()->json(User::where('name', 'like', '%'.$request->name.'%')->get(),200);
        elseif( $request->Surname )
            return response()->json(User::where('Surname', 'like', '%'.$request->Surname.'%')->get(),200);
        elseif( $request->Login )
            return response()->json(User::where('Login', 'like', '%'.$request->Login.'%')->get(),200);
        elseif( $request->id_City )
            return response()->json(User::where('id_City', $request->id_City )->get(),200);
        elseif( $request->id_Country )
            return response()->json(User::where('id_Country', $request->id_Country)->get(),200);
        elseif( $request->Privilege )
            return response()->json(User::where('Privilege', $request->Privilege)->get(),200);
        else
            return response()->json("Данные не найдены",403,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);
    }

    public function delete( User $user ){
        if( $user->Privilege != 4){
            $user->delete();
            return response()->json(  null, 204 );
        }
        else
            return response()->json("Вы не можете удалять админов",400,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);
    }

    public function adminGetImage( Request $request, User $user )
    {
        return ImageController::getImage( $request, $user );
    }

    public function adminUploadImage( Request $request, User $user )
    {
        return ImageController::uploadImage( $request, $user );
    }
}
