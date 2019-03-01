<?php

namespace App\Http\Controllers\Auth;

use Session;
use Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('logout');
        $this->middleware('guest')->except('logout');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $isLoggedIn = false;
        if (Auth::check()) {
            $isLoggedIn = true;
            $user = Auth::user();
            Log::info("User: $user->name is attempting to log out.");
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request, $isLoggedIn) ?: redirect('/login');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request, $isLoggedIn)
    {
        if ($isLoggedIn) {
            Session::flash('logoutMessage', 'You are now logged out.');
        }
        Log::info("A user has been logged out.");
        return redirect('/login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $validation = $this->attemptLogin($request);
        if ($validation == "active") {
            $name = $this->guard()->user()->name;
            Log::info("A user has logged in. User: $name");
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request, $validation);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        if ($this->guard()->attempt($this->credentials($request), true)) {
            if ($this->guard()->user()->hasVerifiedEmail()) {
                if ($this->guard()->user()->IsActive()) {
                    return "active";
                } else {
                    $this->guard()->logout();
                    return "inactive";
                }
            } else {
                /*
                $email = $this->guard()->user()->email;
                Mail::to($email)->send(new DemoMail());
                */

                $this->guard()->logout();
                return "unverified";
            }
        } else {
            return "failed";
        }
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request, $type)
    {
        if ($type == "failed") {
            Log::info("A user attempted to log in but failed.");
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }
        if ($type == "unverified") {
            Log::info("A user attempted to log in but their e-mail address is unverified.");
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.unvalidated')],
            ]);
        }
        if ($type == "inactive") {
            Log::info("A user attempted to log in but their account is inactive.");
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.inactive')],
            ]);
        }
    }
}
