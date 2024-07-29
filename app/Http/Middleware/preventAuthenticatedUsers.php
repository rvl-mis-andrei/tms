<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class preventAuthenticatedUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {

            $cluster = $request->segment(2);
            $user = Auth::user() ?? false;
            $role = strtolower($user->user_roles->role->name);

            return redirect("tms/$cluster/$role/dashboard");

        }

        return $next($request);
    }
}
