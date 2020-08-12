<?php 

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

/**
 * @group User management
 *
 * APIs for managing users
 */

class AuthController extends Controller
{
    // urlParam, queryParam, bodyParam, response, required

    /**
     * Login with email and password
     *
     * @queryParam email required The email of the user
     * @queryParam password required The password of the user
     * 
     * @response 401 {
     *  "error":"Unauthorized"
     * }
     * 
     * @response {
     *   "access_token": "token",
     *   "token_type": "bearer",
     *   "expires_in": 3600
     * }
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Login with authorization header
     *
     * @bodyParam Authorization required Basic base64_encode.Example: Bearer asdasd
     * @response 401 {
     *  "error":"Unauthorized"
     * }
     * @response {
     *   "access_token": "token",
     *   "token_type": "bearer",
     *   "expires_in": 3600
     * }
     */

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
     * Registration user
     *
     * @queryParam login required The login of the user. Max length: 64, must be unique
     * @queryParam email required The email of the user
     * @queryParam password required The password of the user. Rules: min 6 in length, must contain at least one lowercase letter, at least one uppercase letter, at least one digit, a special character
     * @response {
     *   "message":"Successfully registration!"
     * }
     * @response 401{
     *   "error":"Text of the error"
     * }
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
                'min:6',             // must be at least 6 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
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

        return response()->json(['message' => 'Successfully registration!'],200);
    }

    /**
     * Get info about authorizated user
     *
     * @authenticated
     * @response {
     *   "id": 1,
     *   "Surname": null,
     *   "Login": "admin",
     *   "name": "Vasya",
     *   "email": "admin@google.com",
     *   "email_verified_at": null,
     *   "created_at": "2020-07-31T10:51:52.000000Z",
     *   "updated_at": "2020-07-31T10:51:52.000000Z",
     *   "id_Group": null,
     *   "LastLogin": null,
     *   "id_City": null,
     *   "id_Country": null,
     *   "Privilege": 4,
     *   "Avatar": "images/none.jpg"
     * }
     * @response 401{
     *  "error":"Unauthorized"
     * }
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Leave account
     *
     * @authenticated
     * @response {
     *  "message":"Successfully logged out"
     * }
     * @response 401{
     *  "error":"Unauthorized"
     * }
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'],200);
    }

    /**
     * Refresh a token.
     *
     * @authenticated
     * @response {
     *   "access_token": "token",
     *   "token_type": "bearer",
     *   "expires_in": 3600
     * }
     * @response 401{
     *  "error":"Unauthorized"
     * }
     */
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
