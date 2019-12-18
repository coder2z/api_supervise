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
            $data = FeedBack::join('users as userFrom','from_uesr_id','userFrom.id')
                ->join('users as userTo','to_user_id','userTo.id')
                ->select('content->title as title', 'userFrom.name as nameFrom', 'userTo.name as nameTo', 'content->type as type', 'feed_backs.updated_at', 'feed_backs.created_at')
                ->get()
                ->toArray();
            return response()->success(200, "成功", $data);
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('展示所有信息失败!', [$e->getMessage()]);
            return response()->fail(100, "失败");
        }
    }

    //我反馈的信息
    public function fromMessage()
    {
        try {
            $id = auth()->id();
            $data = FeedBack::join('users as userFrom','from_uesr_id','userFrom.id')
                ->join('users as userTo','to_user_id','userTo.id')
                ->select('content->title as title', 'userFrom.name as nameFrom', 'userTo.name as nameTo', 'content->type as type', 'feed_backs.updated_at', 'feed_backs.created_at')
                ->where('from_user_id', $id)
                ->get()->toArray();
            return response()->success(200, "成功", $data);
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('我反馈的信息信息失败!', [$e->getMessage()]);
            return response()->fail(100, "失败");
        }
    }

//发送给我的
    public function toMessage()
    {
        try {
            $id = auth()->id();
            $data = FeedBack::join('users as userFrom','from_uesr_id','userFrom.id')
                ->join('users as userTo','to_user_id','userTo.id')
                ->select('content->title as title', 'userFrom.name as nameFrom', 'userTo.name as nameTo', 'content->type as type', 'feed_backs.updated_at', 'feed_backs.created_at')
                ->where('to_user_id', $id)
                ->get()->toArray();
            return response()->success(200, "成功", $data);
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('发送给我的信息失败!', [$e->getMessage()]);
            return response()->fail(100, "失败");
        }
    }
}

