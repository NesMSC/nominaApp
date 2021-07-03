<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Persona;
use App\User;
use App\Mail\userSendRecover;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

   use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login', ['condicion' => 1]);
    }

    public function login(Request $request)
    {

        $credentials = $request->only('name', 'password');

        if(Auth::attempt($credentials)){
            return redirect($this->redirectTo);
        }else{
            return $this->sendFailedLoginResponse($request);
        }
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'name' => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function getRecover(){
        return view('auth.recuperar');
    }

    public function postRecover(Request $request)
    {

       $rules = [
            'email' => 'required|email'
        ];

        $messages = [
            'email.required' => 'Su correo electrónico es requerido',
            'email.email' => 'El formato de su correo electrónico es inválido'
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('message', 'Se ha producido un error.')->with('typealert', 'danger');
        }else{
            $user = Persona::where('correo', $request->input('email'))->count();
            if ($user == '1') {
                $user = Persona::where('correo', $request->input('email'))->first();
                $code = rand(100000, 999999);
                $data = ["nombres" => $user->nombres, "apellidos" => $user->apellidos, "email" => $user->correo, "code" => $code];
                Mail::to($user->correo)->send(new userSendRecover($data));
                $u = User::find($user->user_id);
                $u->password_code = $code;
                if ($u->save()) {
                    return redirect('/reset?email='.$user->correo)->with('message', 'Ingrese el código de seguridad.')->with('typealert', 'info');
                }
                
            }else{
                return back()->with('message', 'El correo ingresado no existe.')->with('typealert', 'danger');
            };
        }
    }

    public function getReset(Request $request)
    {
        $data = ['email' => $request->get('email')];
        return view('auth.reset', $data);
    }

    public function passwordReset(Request $request)
    {
        $email = $request->input('email');
        $codigo = $request->input('codigo');

        $user = Persona::join('users', 'personas.user_id', 'users.id')
                        ->select('personas.correo', 'users.password_code', 'users.id')
                        ->where('personas.correo', $email)
                        ->first();

        if (count([$user]) == '1' && $user->password_code == $codigo) {

            return view('auth.passwordReset', ["id" => $user->id]);
        }else{
            return back()->with('message', 'Código de seguridad incorrecto')->with('typealert', 'danger');
        }

    }

    public function updateNewPassword(Request $request)
    {

        if ($request->input('confirmacion') != $request->input('contraseña')) {
            return back()->with('message', 'Las contraseñas no coinciden.')->with('typealert', 'danger');
        };

        $user = User::find($request->input('id'));

        $user->password = Hash::make($request->input('contraseña'));
        $user->password_code = null;
        if ($user->save()) {
            return redirect('/login')->with('message', 'Contraseña reestablecida, inicie sesión')->with('typealert', 'success');
        }
        
    }

}
