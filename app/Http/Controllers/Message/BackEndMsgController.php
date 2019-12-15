<?php

namespace App\Http\Controllers\Message;

use App\Model\InterfaceTable;
use Cassandra\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\FeedBack;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use mysql_xdevapi\Exception;
use App\Observers\InterfaceTableObserver;

class BackEndMsgController extends Controller
{
    //展示所有信息
    public function showMessage()
    {
        try {
            $data = FeedBack::select('content->title as title', 'from_user_id', 'to_user_id', 'content->type as type', 'updated_at', 'created_at')->get()->toArray();
            return response()->success(200, "成功", $data);
        } catch (\Exception $e) {
            return response()->fail(100, "失败");
        }
    }

    //我反馈的信息
    public function fromMessage()
    {
        try {
            $id = Auth::id;
            $data = FeedBack::select('content->title as title', 'from_user_id', 'to_user_id', 'content->type as type', 'updated_at', 'created_at')->where('from_user_id', $id)->get()->toArray();
            return response()->success(200, "成功", $data);
        } catch (\Exception $e) {
            return response()->fail(100, "失败");
        }
    }

//发送给我的
    public function toMessage()
    {
        try {
            $id = Auth::id();
            $data = FeedBack::select('content->title as title', 'from_user_id', 'to_user_id', 'content->type as type', 'updated_at', 'created_at')->where('to_user_id', $id)->get()->toArray();
            return response()->success(200, "成功", $data);
        } catch (\Exception $e) {
            return response()->fail(100, "失败");
        }
    }
}

