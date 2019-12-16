<?php

namespace App\Http\Middleware\jurisdiction;

use App\Model\ProjectMember;
use Closure;

class VerifyQueenAdmin
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
        if (auth()->user()->access_code == '0') {
            $data = ProjectMember::where('project_id', $request->project_id)
                ->where('user_id', auth()->id())
                ->where('type', '1')
                ->first();
            if ($data != null) {
                return $next($request);
            }
            return response()->fail(403, '权限不足!', null, 403);
        }
        return response()->fail(403, '权限不足!', null, 403);
    }
}
