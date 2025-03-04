<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {


        if (Auth::check()) {
            // Fetch the authenticated staff's data
            $staff = DB::table('staff')->where('id', Auth::id())->first();

            // Check if the staff member is an admin
            if ($staff && $staff->is_admin == 1) {
                return $next($request);
            }
        }

        // Show 404 page if not an admin
        return abort(404);
    }
}
