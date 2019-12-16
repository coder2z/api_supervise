<?php

namespace App\Http\Controllers\BackendManager;

use App\Http\Requests\BackendManage\ErrorCodeRequest;
use App\Model\Error;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ErrorCodeSettingController extends Controller
{
    //2.错误码设置
    //查询错误代码
    public function selectErrorCode(){
        $data=Error::selectErrorCodeMethod();
        return $data != null ?
            response()->success(200,'获取错误码成功',$data):
            response()->fail(100,'获取错误码失败');
    }
    //新增错误码
    public function addErrorCode(ErrorCodeRequest $request){
        $input = array(
            "project_id"=>intval($request['project_id']),
            "error_code"=>intval($request['error_code']),
            "error_info"=>$request['error_info'],
            "http_code"=>intval($request['http_code']),
        );
        $error = Error::addErrorCodeMethod($input);
        return $error != null ?
            response()->success(200,'新增错误码设置成功'):
            response()->fail(100,'新增错误码设置失败');
    }
    //查询单个错误码信息
    public function oneSelectErrorCode($m_id){
        $data = Error::oneSelectErrorCodeMethod($m_id);
        return $data != null ?
            response()->success(200,'获取单个错误码成功',$data):
            response()->fail(100,'获取单个错误码失败');
    }
    //编辑错误码
    public function editErrorCode(ErrorCodeRequest $request,$m_id){
        $input = array(
            "error_code"=>intval($request['error_code']),
            "error_info"=>$request['error_info'],
            "http_code"=>intval($request['http_code']
            ));
        $error = Error::editErrorCodeMethod($input,$m_id);
        return $error != null ?
            response()->success(200,'修改错误码设置成功'):
            response()->fail(100,'修改错误码设置失败');
    }
    //删除错误码
    public function deErrorCode($m_id){
        $error = Error::deErrorCodeMethod($m_id);
        return $error != null ?
            response()->success(200,'删除错误码设置成功'):
            response()->fail(100,'删除错误码设置失败');
    }
}
