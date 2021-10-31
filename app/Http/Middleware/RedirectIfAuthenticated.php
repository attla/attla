<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class RedirectIfAuthenticated
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
            if (auth($guard)->check()) {
                // return redirect()->route('');
            }
        }

        return $next($request);
    }
}
