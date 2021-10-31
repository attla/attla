<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class Authenticated
{
    /**
     * Handle an incoming request
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null ...$guards
     * @return mixed
     */
    public function handle(Request $request, \Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (auth($guard)->guest()) {
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
