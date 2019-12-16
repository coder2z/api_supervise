<?php

namespace App\Http\Controllers\Message;

use App\Http\Requests\FeedBack\FrontEndInterfaceControllerCheck;
use App\Model\FeedBack;
use App\Http\Controllers\Controller;
use App\Model\Mutual;

class FrontEndInterfaceController extends Controller
{
    public  function FrontEndInterfaceController(FrontEndInterfaceControllerCheck $request)
    {
        try{
            $interface_id=$request->interface_id;
            $front_user_id=Mutual::select('front_user_id')
                ->where('interface_id',$interface_id)
                ->first();
            $front_user_id=['',$front_user_id];
            $front_user_id=(int)$front_user_id;
            $feedBack = new FeedBack();
            $result['title']= $request->input('title');
            $result['body'] = $request->input('body');
            $result ['type']= $request->input('type');
            $feedBack->to_user_id=$front_user_id;
            $feedBack->from_user_id=$request->from_user_id;
            $feedBack->project_id=$request->project_id;
            $feedBack->interface_id=$request->interface_id;
            $feedBack->content=json_encode($result);
            $feedBack->save();
            $content=[''=>$feedBack->content];
            $to=FeedBack::join('users','feed_backs.to_user_id','users.id')
                ->join('mutuals','feed_backs.to_user_id','mutuals.front_user_id')
                ->where('front_user_id',$front_user_id)
                ->select('email')
                ->first();
            $to=$to->toArray();
            $user=$to['email'];
            $this->dispatch(new \App\Jobs\FeedBack\SendEmail($user,$content));
            return response()->success(200, "成功");
        }catch (\Exception $e){
            return response()->fail(100, "失败");
        }

    }


}
