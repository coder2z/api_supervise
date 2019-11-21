<?php

namespace App\Http\Middleware\OAuth;

use Closure;

class VerifyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            return $next($request);
        } else {
            return response()->fail(100, '请先登陆!', null, 401);
        }
    }
}
