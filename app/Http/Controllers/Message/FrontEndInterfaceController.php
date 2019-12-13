<?php

namespace App\Http\Controllers\Message;

use App\Http\Requests\Message\FrontEndInterfaceControllerCheck;
use App\Model\FeedBack;
use App\Http\Controllers\Controller;

class FrontEndInterfaceController extends Controller
{
    public function FrontEndInterfaceController(FrontEndInterfaceControllercheck $request)
    {
        $feedBack = new FeedBack();
        $feedBack->to_user_id = $request->to_user_id;
        $feedBack->from_user_id = auth()->id();
        $feedBack->project_id = $request->project_id;
        $feedBack->content = $request->back_concent;
        $feedBack->save();
        if ($feedBack) {
            return Response()->success(200, '成功', $feedBack);
        } else {
            return response()->fail(100, '失败');
        }

    }
}
