<?php 

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator, Hash, DB, Mail, Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ];

        $messages = [
            'email.required' => "Введите почту",
            'email.exists' => "Неправильная почта либо пароль",
            'email' => "Неправильная форма почты",
            'password.required' => "Введите пароль"
        ];

        $validation = \Validator::make( $credentials, $rules );

        if($validation->fails()) {
            return response()->json([ "error" => $validation->messages()->first() ], 401);
        }

        try {
            if ( ! $token = auth()->attempt($credentials) ) {
                return response()->json([ 'error' => 'Почта либо пароль неверные. Пожалуйста, проверьте правильность введённой информации.'], 404, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'error' => 'Ошибка авторизации, повторите ещё раз'], 500, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
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

    public function recover(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $error_message = "Ваша почта не найдена.";
            return response()->json(['success' => false, 'error' => ['email'=> $error_message]], 400, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        try {
            $name = $user->Login;
            $email = $user->email;
            $verification_code = Str::random(30); 
            DB::table('password_resets')->insert(['email'=>$user->email,'token'=>$verification_code]);

            $subject = "Сброс пароля.";
            Mail::send('reset', ['name' => $name, 'verification_code' => $verification_code],
                function($mail) use ($email, $name, $subject, $verification_code){
                    $mail->from(getenv('MAIL_FROM_ADDRESS'), "Stuhelp")
                    ->to($email, $name)
                    ->subject($subject)
                    ->addPart('Привет, '.$name.'.
                    Это сообщение было отправлено с сайта stuhelp.site, так как вы решили сменить ваш пароль. Если нет - можете начинать беспокоится.
                    Cкопируйте ее в адресную строку браузера, чтобы сменить пароль:
                    https://stuhelp.site/api/user/password/'.$verification_code);
                }
            );

        } catch (\Exception $e) {
            //Return with error
            $error_message = $e->getMessage();
            return response()->json(['success' => false, 'error' => $error_message], 401);
        }

        return response()->json([
            'success' => true, 'data'=> ['message'=> 'Письмо для смены пароля было отправлено! Проверьте ваше почту'],
            400, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE
        ]);
    }

    public function resetPasswordView($verification_code)
    {
        $check = DB::table('password_resets')->where('token',$verification_code)->first();

        if(!is_null($check))
            return view('resetpassword',["token" => $verification_code]);

        return response()->json(['success'=> false, 'error'=> "Токен неверный"]);

    }

    public function resetPassword($verification_code, Request $request )
    {
        $check = DB::table('password_resets')->where('token',$verification_code)->first();

        if(!is_null($check)){
            $user = User::where('email',$check->email)->first();

            if( $request->password !== $request->repeatPassword ){
                return response()->json([
                    'success'=> false,
                    'message'=> 'Пароли не совпадают'
                ],400, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }

            $inputs = [
                "password" => $request->password
            ];

            $rules = [
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

            $user->update(['password' => Hash::make( $request->password )]);
            DB::table('password_resets')->where('token',$verification_code)->delete();

            return response()->json([
                'success'=> true,
                'message'=> 'Вы успешно сменили пароль!'
            ],200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        return response()->json(['success'=> false, 'error'=> "Токен неверный"], 
        400, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);

    }

    public function verifyUser($verification_code)
    {
        $check = DB::table('user_verifications')->where('token',$verification_code)->first();

        if(!is_null($check)){
            $user = User::find($check->user_id);

            if($user->is_verified == 1){
                return response()->json([
                    'success'=> true,
                    'message'=> 'Аккаунт уже подтвержден'
                ],
                400, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }
            
            $user->update(['is_verified' => 1]);
            DB::table('user_verifications')->where('token',$verification_code)->delete();

            return response()->json([
                    'success'=> true,
                    'message'=> 'Вы успешно подтвердили свой аккаунт!'
                ],
                400, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE
            );
        }

        return response()->json(['success'=> false, 'error'=> "Код верификации неверный"],
        400, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);

    }

    public function registration()
    {
        $inputs = [
            'Login'    => request('login'),
            'email'    => request('email'),
            'password' => request('password'),
        ];
    
        $rules = [
            'Login'    => 'required|unique:users|max:64|min:3',
            'email'    => 'required|email|unique:users',
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

        $messages = [
            'Login.unique' => "Пользователь с таким логином уже существует",
            'Login.required' => "Введите логин",
            'Login.max' => "Логин слишком длинный. Максимальный размер - 64 символа",
            'Login.min' => "Логин слишком короткий. Минимальный размер - 3 символа",
            'email.required' => "Введите почту",
            'email.unique' => "Пользователь с такой почтой уже существует",
            'email.email' => "Неправильная форма почты",
            'password.required' => "Введите пароль",
            'password.regex' => "Неверный формат пароля.",
            'password.min' => "Пароль слишком короткий. Минимальный размер - 6 символов",
        ];
    
        $validation = \Validator::make( $inputs, $rules , $messages );
    
        if ( $validation->fails() ) {
            return response()->json(['error' => $validation->errors()->all() ],401);
        }


        $user = new User();
        $user->Login = $inputs['Login'];
        $user->email = $inputs['email'];
        $user->password = Hash::make($inputs['password']);
        $user->save();

        $name = $user->Login;
        $email = $user->email;

        $verification_code = Str::random(30);
        DB::table('user_verifications')->insert(['user_id'=>$user->id,'token'=>$verification_code]);

        $subject = "Пожалуйста, подтвердите свою почту.";
        Mail::send('verify', ['name' => $name, 'verification_code' => $verification_code],
            function($mail) use ($email, $name, $subject, $verification_code){
                $mail->from(getenv('MAIL_FROM_ADDRESS'), "Stuhelp")
                ->to($email, $name)
                ->subject($subject)
                ->addPart('Привет, '.$name.'.Спасибо за регистрацию на сайте stuhelp.site! Не забудь закончить регистрацию в своем профиле.Cкопируйте ссылку в адресную строку браузера, чтобы подтвердить свой адрес электронной почты: https://stuhelp.site/api/user/verify/'.$verification_code);
            }
        );
        return response()->json(['message' => 'Вы успешно зарегистрировались! На вашу почту было отправлено сообщение для подтверждения.'],200,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);
    }

    public function sendMail( Request $request ){
        Mail::send('mailToAdmin', ['name' => $request->name, 'text' => $request->text],
            function($mail) use( $request ){
                $mail->from( $request->email, $request->name );
                $mail->to(getenv('MAIL_FROM_ADDRESS'), "Stuhelp");
                $mail->subject($request->subject);
            }
        );

        return response()->json("Отправленно",200,["Content-type" => "application/json"], JSON_UNESCAPED_UNICODE);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Успешно вышли'],200);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'message' => 'Успешная авторизация',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
