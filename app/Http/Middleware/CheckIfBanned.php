<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfBanned
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
        if (Auth::check() && Auth::user()->banned) {
            // Optionally, you can log out the user
            Auth::logout();

            // Redirect the user with an error message
            return redirect('/login')->withErrors(['Your account has been banned.']);
        }

        return $next($request);
    }
}

