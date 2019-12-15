<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\ProjectMember;
use App\Model\Project;
use App\Model\Mutual;
use App\Utils\Logs;

define('PAGENUM',env('PAGE_NUM'));

class InterfaceManagerController extends Controller
{
    //获取接口列表
    public function index(){
        $user_id = auth()->id();
        $indexTable = Project::join('project_members','projects.id','project_members.project_id')
                                ->join('project_modules','projects.id','project_modules.project_id')
                                ->join('interface_tables','project_modules.id','interface_tables.module_id')
                                ->join('request_tables','interface_tables.id','request_tables.interface_id')
                                ->leftjoin('mutuals','interface_tables.id','mutuals.interface_id')
                                ->select(['interface_tables.id as interface_id',
                                            'interface_tables.interface_name',
                                            'interface_tables.route_path as interface_url',
                                            'request_tables.request_mode as interfaec_method',
                                            'mutuals.interface_id as interface_is_interactive',
                                            'projects.name as interface_belong_to',])
                                ->where('project_members.user_id',$user_id)
                                ->paginate(PAGENUM);
        Logs::logInfo("用id为{$user_id}获取接口列表.");
        return response()->success(200,'成功',$indexTable);
    }
    //接口名搜索接口
    public function searchInterface(Request $request){
        $user_id = auth()->id();
        
        $searchTable = Project::join('project_members','projects.id','project_members.project_id')
                                ->join('project_modules','projects.id','project_modules.project_id')
                                ->join('interface_tables','project_modules.id','interface_tables.module_id')
                                ->join('request_tables','interface_tables.id','request_tables.interface_id')
                                ->leftjoin('mutuals','interface_tables.id','mutuals.interface_id')
                                ->select(['interface_tables.id as interface_id',
                                            'interface_tables.interface_name',
                                            'interface_tables.route_path as interface_url',
                                            'request_tables.request_mode as interfaec_method',
                                            'mutuals.interface_id as interface_is_interactive',
                                            'projects.name as interface_belong_to',])
                                ->where('project_members.user_id',$user_id)
                                ->where('interface_tables.interface_name','like','%'.$request->search_content.'%')
                                ->paginate(PAGENUM);
        Logs::logInfo("用id为{$user_id}搜索接口列表.搜索内容:\"{$request->search_content}\"");
        return response()->success(200,'成功',$searchTable);
    }
    //设置交互状态
    public function setInteractiveState($interface_id){
        $user_id = auth()->id();
        $mutual = Mutual::where('interface_id',$interface_id)->first();
        if(isset($mutual)){
            if($mutual->delete()){
                Logs::logInfo("用id为{$user_id}取消接口id{$interface_id}交互.");
                return response()->success(200,'修改成功');
            }
        }else{
            $mutual = new Mutual;
            $mutual->interface_id = $interface_id;
            $mutual->front_uesr_id = $user_id;
            if($mutual->save()){
                Logs::logInfo("用id为{$user_id}给接口id{$interface_id}交互.");
                return response()->success(200,'修改成功');
            }
        }
    }
}
