<?php

namespace App\Http\Controllers\ProjectAdmin;

use App\Http\Requests\ProjectAdmin\searchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\ProjectMember;
use App\Model\ProjectModule;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    //显示全部人员
    public function getAllUsers(){
        $userInfo = User::getAllUsers();
        return $userInfo != null ?
            response()->success(200, '获取成功!', $userInfo) :
            response()->fail(100, '获取失败!');
    }

    //获取要修改的人员
    public function getUpdateUser($id){
        $res = User::getUpdateUser($id);
        return $res != null ?
            response()->success(200, '获取成功!', $res) :
            response()->fail(100, '获取失败!');
    }

    //修改人员
    public function updateUser(Request $request,$id){
        $res = User::updateUser($request,$id);
        return $res != 0 ?
            response()->success(200, '修改成功!', $res) :
            response()->fail(100, '修改失败!');
    }

    //移除人员
    public function deleteUser($id){
        $pname = Input::get('pname');
        $res = User::deleteUser($pname,$id);
        return $res != 0 ?
            response()->success(200, '移除成功!', $res) :
            response()->fail(100, '移除失败!');
    }

    //获取人员（根据传入值得不同获取不同人员）
    public function getUsers(){
        $data = Input::all();
        if($data != null){
            $res = User::getUsers($data);
            return $res != null ?
                response()->success(200, '获取成功!', $res) :
                response()->fail(100, '获取失败!');
        }else{
            response()->fail(100, '参数未传');
        }
    }

    //搜索人员
    public function searchUser(searchRequest $request){
        $data = $request->Content;
        $res = User::searchUser($data);
        if($res['data'] == null){
            return response()->success(200, '未查询到数据!', null);
        }else{
            return response()->success(200, '获取成功!', $res);
        }
    }
}