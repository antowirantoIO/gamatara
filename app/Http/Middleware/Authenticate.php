<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $route_path = $request->route()->uri();
        $current_route = explode('/', $route_path);
        if (! $request->expectsJson()) {
            if(($current_route[0] ?? null) == 'api') {
                return route('unauthorized');
            } else {
                return route('login');
            }
        }
    }
}
