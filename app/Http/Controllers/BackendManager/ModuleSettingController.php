<?php

namespace App\Http\Controllers\BackendManager;

use App\Http\Requests\BackendManage\ModuleRequest;
use App\Model\ProjectModule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModuleSettingController extends Controller
{
    //项目管理
    //1.模块设置
    public function selectModule()
    {
        $data = ProjectModule::selectModuleMethod();
        return $data != null ?
            response()->success(200, '获取成功', $data) :
            response()->fail(100, '获取失败');
    }

    //新增模块
    public function addModule(ModuleRequest $request)
    {
        $input = array(
            "modules_name" => $request['modules_name'],
            "utility" => $request['utility'],
            "class_name" => $request['class_name'],
            "full_class_name" => $request['full_class_name'],
        );
        $module = ProjectModule::addModuleMethod($input);
        return $module != null ?
            response()->success(200, '添加模块设置成功') :
            response()->fail(100, '添加模块设置失败');
    }

    //编辑模块
    public function editModule(ModuleRequest $request)
    {
        $m_id = $request['m_id'];
        $input = array(
            "modules_name" => $request['modules_name'],
            "utility" => $request['utility'],
            "class_name" => $request['class_name'],
            "full_class_name" => $request['full_class_name']
        );
        $module = ProjectModule::editModuleMethod($input, $m_id);
        return $module != null ?
            response()->success(200, '编辑模块设置成功') :
            response()->fail(100, '编辑模块设置失败');
    }

    //删除模块
    public function deModule(Request $request)
    {
        $m_id = $request['m_id'];
        $module = ProjectModule::deModuleMethod($m_id);
        return $module != null ?
            response()->success(200, '删除模块设置成功') :
            response()->fail(100, '删除模块设置失败');
    }
}
