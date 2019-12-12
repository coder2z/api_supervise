<?php

namespace App\Http\Middleware\Admin;

use Closure;
use App\Model\User;
use Illuminate\Support\Facades\Auth;

class checkManage
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
        if(Auth::check()){
            $code=User::where('id',Auth::id())->select('access_code')->first()->access_code;
            if($code!=1){
                return response()->fail(403,'失败','权限不足',403);
            }
            return $next($request);
        }
        return response()->fail(403,'失败','权限不足',403);
    }
}
