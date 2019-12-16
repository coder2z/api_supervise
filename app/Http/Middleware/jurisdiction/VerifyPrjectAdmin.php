<?php

namespace App\Http\Middleware\jurisdiction;
use Closure;

class VerifyPrjectAdmin
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
        if (auth()->user()->access_code == '-1') {
            return $next($request);
        }
        return response()->fail(403, '权限不足!', null, 403);
    }
}
