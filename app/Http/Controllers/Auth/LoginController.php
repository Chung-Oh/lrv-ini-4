<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    public function loginSocial(Request $request)
    {
        $this->validate($request, [
            'social_type' => 'required|in:google,github'
        ]);
        $socialType = $request->get('social_type');
        \Session::put('social_type', $socialType);

        return Socialite::driver($socialType)->redirect();
    }

    public function loginCallback()
    {
        $socialType = \Session::pull('social_type');
        $userSocial = Socialite::driver($socialType)->user();
        $user = User::where('email', $userSocial->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $userSocial->name,
                'email' => $userSocial->email,
                // 'password' => bcrypt(str_random(10)),
                'password' => bcrypt('123456'),
                'role' => User::ROLE_USER,
                'phone' => '',
                'cpf' => ''
            ]);
        }
        \Auth::login($user);
        return redirect()->intended($this->redirectPath());
    }

    public function redirectTo()
    {
        return \Auth::user()->role == User::ROLE_ADMIN ? '/admin/home' : '/home';
    }

    public function logout(Request $request)
    {
        $role = (int) $request->user()->role;
        $this->guard()->logout();
        $request->session()->invalidate();

        return $role == User::ROLE_ADMIN ? redirect('admin/login') : redirect('/login');
    }

    protected function credentials(Request $request)
    {
        $data = $request->only($this->username(), 'password');
        // $data['phone'] = $data['email'];
        $username = $this->userNameKey();

        if ($username != $this->username()) {
            $data[$this->userNameKey()] = $data[$this->username()];
            unset($data[$this->username()]);
        }

        return $data;
    }

    protected function userNameKey()
    {
        $email = \Request::get('email');
        // $validator = \Validator::make([
        //     'email' => $email
        // ], ['email' => 'cpf']);

        // if (!$validator->fails()) {
        if (strlen($email) == 11) {
            return 'cpf';
        }

        if (is_numeric($email)) {
            return 'phone';
        }

        return 'email';
    }
}
