<?php

namespace App\Http\Controllers\ProjectAdmin;

use App\Http\Requests\ProjectAdmin\QueryUsers;
use App\Model\ProjectMember;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use phpDocumentor\Reflection\Project;

class InviteController extends Controller
{
    Public function getMembersItem(){
        $UserAll = User::selectUser();
        return $UserAll != null ?
            response()->success(200, '获取成功!', $UserAll) :
            response()->fail(100, '获取失败!');
    }

    public function addMembers(){
        $userid = Input::get('userid');
        $itemid = Input::get('itemid');
        $userid = explode(',',$userid);

        if(ProjectMember::addMembers($userid,$itemid)){
            return response()->success(200, '获取成功!', null);
        }else{
           return response()->fail(100, '添加成员失败!');
        }
    }


    public function queryUsers(QueryUsers $queryUsers){
        $data = User::queryUsers($queryUsers['str']);
        return $data != null ?
            response()->success(200, '获取成功!', $data) :
            response()->fail(100, '获取失败!');
    }
}
