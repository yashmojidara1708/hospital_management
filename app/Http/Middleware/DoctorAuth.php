<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorAuth
{
    public function handle($request, Closure $next)
    {

        if (Auth::check()) {
            // Fetch the authenticated staff's data
            $doctors = DB::table('doctors')->where('id', Auth::id())->first();

            // Check if the staff member is an admin
            if ($doctors && $doctors->is_admin == 1) {
                return $next($request);
            }
        }

        // Show 404 page if not an admin
        return abort(404);
    }
}
