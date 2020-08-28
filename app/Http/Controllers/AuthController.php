<?php 

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        date_default_timezone_set("Europe/Kiev");
        auth()->user()->update([ "LastLogin" => date("Y-m-d H:i:s") ]);
        return $this->respondWithToken($token);
    }

    public function loginTest( Request $request )
    {
        //$base64 = explode( ' ', $request->header('Authorization') ); // get base64 string
        //$credentials = explode( ':', base64_decode( end($base64) ) ); // get email:password

        if ( ! isset($_SERVER['PHP_AUTH_USER']) || 
             ! $token = auth()->attempt( 
                 [ 
                     "email" => $_SERVER['PHP_AUTH_USER'], 
                     "password" => $_SERVER['PHP_AUTH_PW'] 
                 ] 
            ) 
        ) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
        date_default_timezone_set("Europe/Kiev");
        auth()->user()->update([ "LastLogin" => date("Y-m-d H:i:s") ]);
        return $this->respondWithToken($token);
    }

    public function registration()
    {
        //$login = request('login');
        //$email = request('email');
        //$password = request('password');

        $inputs = [
            'Login'    => request('login'),
            'email'    => request('email'),
            'password' => request('password'),
        ];
    
        $rules = [
            'Login'    => 'required|unique:users|max:64',
            'email'    => 'required|email',
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/', 
            ],
        ];
    
        $validation = \Validator::make( $inputs, $rules );
    
        if ( $validation->fails() ) {
            return response()->json(['error' => $validation->errors()->all() ],401);
        }


        $user = new User();
        $user->Login = $inputs['Login'];
        $user->email = $inputs['email'];
        $user->password = Hash::make($inputs['password']);
        $user->save();

        event(new Registered($user));

        return response()->json(['message' => 'Successfully registration!'],200);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'],200);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
