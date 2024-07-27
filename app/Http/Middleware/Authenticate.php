<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {

        $role =$request->segment(3);
        $cluster = $request->segment(2);

        return $request->expectsJson() ? null : route($cluster.'.'.$role.'.'.'form');
    }
}
