<?php

namespace App\Http\Controllers\Message;

use App\Http\Requests\Message\FrontEndInterfaceControllerCheck;
use App\Model\FeedBack;
use App\Providers\ResponseServiceProvider;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model;
use http\Client\Response;

class FrontEndInterfaceController extends Controller
{
    //
    public function FrontEndInterfaceController(FrontEndInterfaceControllercheck $request)
    {

        $title = $request->input('title',[]); // second parameter is to set default value
        $body = $request->input('body',[]);
        $type = $request->input('type',[]);
//        $result=[$title,$body,$type];
        $feedBack = new FeedBack();
        $feedBack->to_user_id = $request->to_user_id;
        $feedBack->from_user_id = $request->from_user_id;
        $feedBack->project_id = $request->project_id;
            $result['title'] = $title;
            $result['body']  = $body;
            $result['type'] = $type;
//         $data=[];
//         $data['content']=$result;
        $feedBack->content = json_encode($result);
        $feedBack->save();
        if($feedBack){
            return Response()->success(200, '成功', $feedBack);
        }else{
            return response()->fail(100,'失败');
        }

    }
}
