<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class IsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return $next($request);
        }

        auth()->logout();
        Session::flash('logoutInactive', 'Your e-mail address is still unvalidated. Please validate your e-mail address before loging in.');
        return redirect('/login');
    }
}
