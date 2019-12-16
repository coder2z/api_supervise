<?php

namespace App\Http\Middleware\jurisdiction;

use App\Model\Position;
use Closure;

class VerifyQueen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->access_code == '0') {
            $data = Position::where('user_id', auth()->id())
                ->where('position_code', '1')
                ->select('position_code')
                ->first();
            if ($data != null) {
                return $next($request);
            }
            return response()->fail(403, '权限不足!', null, 403);
        }
        return response()->fail(403, '权限不足!', null, 403);
    }
}
