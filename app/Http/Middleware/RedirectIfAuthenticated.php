<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard = null)
{
    // If the guard is "admin" and the user is authenticated with that guard
    if ($guard == "admin" && Auth::guard($guard)->check()) {
        // Redirect admin to admin dashboard
        return redirect('/admin');
    }

    // If the guard is "user" and the user is authenticated with that guard
    if ($guard == "user" && Auth::guard($guard)->check()) {
        // Redirect user to author dashboard
        return redirect('/user');
    }

    // If the user is authenticated with the default guard
    if (Auth::guard($guard)->check()) {
        // Redirect to home page for any other authenticated user
        return redirect('/home');
    }

    // Proceed to the next middleware if the user is not authenticated
    return $next($request);
}
}
