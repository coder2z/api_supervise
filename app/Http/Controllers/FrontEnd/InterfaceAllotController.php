<?php

namespace App\Http\Controllers\FrontEnd;

use App\Model\InterfaceTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FrontEnd\sceen;

class InterfaceAllotController extends Controller
{
    //获取所有接口分配
    public function getAllInterface()
    {
        $data = InterfaceTable::getAllInterface(env('PAGE_NUM'));
        if ($data == null) {
            return response()->fail(100, '获取接口失败');
        } else {
            return response()->success(200, '成功', $data);
        }
    }

    //接口分配情况筛选
    public function screenInterface(sceen $request)
    {
        $data = InterfaceTable::screenInterface($request->develop_id, $request->module_id, env('PAGE_NUM'));
        if ($data == null) {
            return response()->fail(100, '获取接口失败');
        } else if ($data == '-1') {
            return response()->fail(100, '该开发人员不存在');
        } else if ($data == '-2') {
            return response()->fail(100, '该模块不存在');
        } else if ($data == '-3') {
            return response()->fail(100, '该开发人员不属于该模块');
        } else {
            return response()->success(200, '成功', $data);
        }
    }
}
