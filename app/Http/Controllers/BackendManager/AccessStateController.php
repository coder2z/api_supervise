<?php

namespace App\Http\Controllers\BackendManager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\InterfaceTable;
use App\Model\Assignment;
use App\Http\Requests\BackendManage\check;

class AccessStateController extends Controller
{
    //获取所有接口
    public function getAPIAll(){
        $data=InterfaceTable::get_API_all(env('PAGE_NUM'));
        if($data==null){
            return response()->fail(100,'获取接口数据失败');
        }
        return response()->success(200,'成功',$data);
    }

    //根据ID获取指定接口的所有信息
    public function getAPIInfoByID(Request $request){
        $id=$request->interface_id;
        $data=InterfaceTable::get_API_info($id);
        if($data=='-1'){
            return response()->fail(100,'接口不存在');
        }else if($data==null){
            return response()->fail(100,'查询接口失败');
        }else{
            return response()->success(200,'成功',$data);
        }
    }

    //修改指定接口审核状态
    public function checkAPI(check $request){
        $id=$request->interface_id;
        $data=InterfaceTable::checkAPI($id,$request->state);
        if($data==0){
            return response()->fail(100,'接口不存在');
        }else if($data==null){
            return response()->fail(100,'修改接口状态失败');
        }else{
            return response()->success(200,'修改成功');
        }
    }

}
