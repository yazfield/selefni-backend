<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function login(Request $request)
    {
        // validate is redirecting
        //$this->validateLogin($request);

        //dd('login');
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        //dd('attempt');
        if ($this->attemptLogin($request)) {
            dd($this->sendLoginResponse($request));
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function username()
    {
        return 'username';
    }

    /**
     * Returns credencials after deciding what is the username field
     * because auth can be done either with email or phone number
     */
    protected function credentials(Request $request)
    {
        $username = $request->only($this->username());
        $username = array_pop($username);
        $credentials = $request->only('password');
        $credentials[username_field($username)] = $username;
        return $credentials;
    }

    protected function authenticated(Request $request, $user)
    {
        return response()->json([
            'message' => 'User authenticated',
            'token' => $request->session()->token(),
        ]);
    }
}
