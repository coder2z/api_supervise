<?php

namespace App\Http\Controllers\Message;

use App\Http\Requests\FrontEndMsg_request;
use App\Jobs\SendEmail;
use App\Model\ProjectMember;
use App\Model\User;
use App\Utils\Logs;
use Mail;
use App\Model\FeedBack;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use mysql_xdevapi\Exception;
class FrontEndMsgController extends Controller
{
    /**@author  niyu
     *发邮件给同一个项目的人
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SendMail_All_Back(FrontEndMsg_request $request)
    {
        try
        {
            $project_id=$request->input("project_id");
            $members=ProjectMember::get_Info($project_id,["user_id"]);
            $users=null;$i=0;
            foreach ($members as $member)
            {
                $user=User::getUserInfo($member->user_id,["email"])->toArray();//collection->array
                $users[$i++]=$user[0]["email"];
            }
            $this->dispatch(new SendEmail($users));
            $res=array("code"=>200,"msg"=>"success","data"=>"邮件已进入队列，正等待处理");
            return response()->json($res);

        }catch (Exception $exception)
        {
            Logs::logError('发送邮件错误!', [$exception->getMessage()]);
            return response()->json(["code"=>100,"msg"=>"failure","data"=>"邮件操作失败"]);
        }
    }

    /**
     * @author niyu
     * 获取反馈信息回显
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
