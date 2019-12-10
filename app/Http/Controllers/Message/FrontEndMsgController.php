<?php

namespace App\Http\Controllers\Message;

use App\Http\Requests\FrontEndMsg_request;
use App\Jobs\SendEmail;
use App\Model\ProjectMember;
use App\Model\User;
use App\Utils\Logs;
use Illuminate\Support\Facades\Auth;
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
    public function SendMail($id=[],$Info)
    {
        try
        {
            $x=0;$members_array=[];
            foreach ($id as $project_id){
                $members_array[$x++]=ProjectMember::get_Info("project_id",$project_id,["user_id"]);
            }
            $users=null;$i=0;
            foreach ($members_array as $members) {
                foreach ($members as $member)
                {
                    $user=User::getUserInfo($member->user_id,["email"])->toArray();//collection->array
                    $users[$i++]=$user[0]["email"];
                }
            }
            $users = Array_unique($users);
            $this->dispatch(new SendEmail($users,$Info));
            return true;
        }catch (Exception $exception)
        {
            Logs::logError('发送邮件错误!', [$exception->getMessage()]);
            return false;
        }
    }
    public  function  SendMail_All_Back(FrontEndMsg_request $request)
    {
        try
        {
            $info=$request;
            $data = new FeedBack;
            $content=[
                "title"=>$info->title,
                "question"=>$info->question,
                "reappear"=>$info->reappear,
                "expect"=>$info->expect,
                "cause"=>$info->cause,
                "suggest"=>$info->suggest,
            ];
            $data->from_user_id=1;
            $data->to_user_id=1;
            $data->interface_id=1;
            $data->broadcast=1;//广播 0（前端） 1（后端） -1（所有）
            $data->content=json_encode($content);
            $data->save();
            $project_ids=ProjectMember::get_Info("user_id",Auth::id(),["project_id"]);
            $project_id=[];
            foreach ($project_ids as $item)
            {
                Array_push($project_id,$item->project_id);
            }
                if($this->SendMail($project_id,$content))
            {
                $res=array("code"=>200,"msg"=>"success","data"=>"邮件已进入队列，正等待处理");
                return response()->json($res);
            }
            else
            {
                $res=array("code"=>200,"msg"=>"false","data"=>"邮件发送出现错误");
                return response()->json($res);
            }
        }
        catch (\Exception $exception)
        {
            Logs::logError('创建反馈信息出错：', [$exception->getMessage()]);
            $res=array("code"=>100,"msg"=>"false","data"=>"创建反馈信息出错");
            return response()->json($res);
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
            //$data= FeedBack::getInfo_echo(Auth::id());
            $data= FeedBack::getInfo_echo(Auth::id());
            $res=array("code"=>200,"msg"=>"success","data"=>$data);
            return response()->json( $res);
        }
        catch (Exception $exception)
        {
            Logs::logError('获取信息出错：', [$exception->getMessage()]);
            $res=array("code"=>100,"msg"=>"false","data"=>"获取息出错");
            return response()->json($exception);
        }
    }
}
