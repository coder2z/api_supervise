<?php

namespace App\Http\Controllers\Message;

use App\Jobs\SendEmail;
use App\Model\FeedBack;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use mysql_xdevapi\Exception;

class FrontEndMsgController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SendMail_All_Back(Request $request)
    {
        $user="1481048825@qq.com";
        $this->dispatch(new SendEmail($user));
        $res=array("code"=>200,"msg"=>"success","data"=>"");
        return response()->json($res);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function MyMessage(Request $request)
    {
        try
        {
           $data= FeedBack::getInfo_echo(1);
            $res=array("code"=>200,"msg"=>"success","data"=>json_encode($data));
            dd($res);
            return response()->json($res);
        }
        catch (Exception $exception)
        {
            return response()->json($exception);
        }
    }
}
