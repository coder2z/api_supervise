<?php

namespace App\Http\Controllers\Message;

use App\Model\FeedBack;
use App\Providers\ResponseServiceProvider;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model;
use http\Client\Response;
use App\Http\Requests\Message\viewFeedBackCheck;
class viewFeedback extends Controller
{
    //
    public function viewFeedback(viewFeedBackCheck $request){
        $from_user_id=$request->from_user_id;
        $to_user_id=$request->to_user_id;
        $data=new FeedBack();
        $data=FeedBack::where('from_user_id',$from_user_id)
            ->where('to_user_id',$to_user_id)
            ->select('content')
            ->get()
            ->toArray();
        if($data){
            return response()->success(200, '成功', $data);
        }else{
            return response()->fail(100,'失败');
        }

    }
}
