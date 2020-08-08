<?php 

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

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

        return $this->respondWithToken($token);
    }

    /**
     * User registration
     */
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
                'min:10',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ];
    
        $validation = \Validator::make( $inputs, $rules );
    
        if ( $validation->fails() ) {
            return response()->json(['error' => $validation->errors()->all() ]);
        }


        $user = new User();
        $user->Login = $inputs['Login'];
        $user->email = $inputs['email'];
        $user->password = Hash::make($inputs['password']);
        $user->save();

        return response()->json(['message' => 'Successfully registration!']);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
