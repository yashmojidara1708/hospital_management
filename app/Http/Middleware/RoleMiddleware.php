<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      /*  if (!Auth::check()) {
            return redirect('/login'); // Redirect if user is not logged in
        }

        if (Auth::user()->role->name !== $role) {
            return redirect('/login')->with('error', 'Unauthorized access'); // Avoid infinite loops
        }*/
       // return redirect('/login')->with('error', 'Unauthorized access');
       return $next($request);
    }
}
