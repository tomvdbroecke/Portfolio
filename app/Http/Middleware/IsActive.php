<?php

namespace App\Http\Middleware;

use Closure;

class IsActive
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
        if (Auth::check() && Auth::user()->IsActive()) {
            return $next($request);
        }

        auth()->logout();
        Session::flash('logoutInactive', "You currently don't have any active projects.");
        return redirect('/login');
    }
}
