<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SetManage;
use App\Model\InterfaceTable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\AddUser;
use App\Http\Requests\Admin\DeleteUser;
use Validator;

class AdminController extends Controller
{
    //获取某个状态下所有用户(分页)
    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getUser(Request $request)
    {
        $array = ['users.id', 'name', 'phone_number', 'email', 'state', 'p.position_code'];
        $code = $request->access_code;
        $data = User::getInfo($code, env('PAGE_NUM'), $array);
        return $data == null ?
            response()->fail(100, '失败') :
            response()->success(200, '成功', $data);
    }

    //删除用户

    /**
     * @param DeleteUser $request
     * @return mixed
     * @throws \Exception
     */
    public function DeleteUser(DeleteUser $request)
    {
        $data = User::adminDeleteUser($request->ID);
        if ($data == null) {
            return response()->fail(100, '失败', '用户不存在');
        } else {
            return response()->success(200, '删除用户成功');
        }
    }

    //搜索用户(分页)
    public function SearchUser(Request $request)
    {
        $array = ['users.id', 'name', 'phone_number', 'email', 'state', 'p.position_code'];
        $data = User::Search($request->info, env('PAGE_NUM'), $array);
        return $data == null ?
            response()->fail(100, '失败') :
            response()->success(200, '成功', $data);
    }

    //获取指定用户信息
    public function ShowUserInfo(DeleteUser $request)
    {
        $array = ['users.id', 'name', 'access_code', 'phone_number', 'email', 'state', 'p.position_code'];
        $data = User::ShowUserInfo($request->ID, $array);
        if (isset($data[0]->id)) {
            return response()->success(200, '成功', $data);
        } else {
            return response()->fail(100, '失败', '用户不存在');
        }
    }

    //修改用户
    public function UpdateUserInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:5',
            'password' => 'check_password',
            'access_code' => 'required|check_code',
            'state' => 'required|check_state',
            'phone' => ['required', 'digits:11', Rule::unique('users', 'phone_number')->ignore($request->ID)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($request->ID)],
        ]);
        if ($validator->fails()) {
            throw (new HttpResponseException(response()->fail(422, '参数错误！', $validator->errors()->all(), 422)));
        }
        if (!isset(User::find($request->ID)->id)) {
            return response()->fail(100, '失败，用户不存在');
        }
        $info = [$request->ID, $request->name, $request->password, $request->phone, $request->email, $request->access_code, $request->state];
        $data = User::UpdateUserInfo($info);
        if (!$data) {
            return response()->fail(100, '修改失败');
        } else {
            $message = $request->name . '被修改了';
            \App\Utils\Logs::logInfo($message, Auth::user());
            return response()->success(200, '修改成功');
        }
    }

    //新建用户
    public function AddUser(AddUser $request)
    {
        $info = [$request->name, $request->password, $request->phone, $request->email, $request->access_code];
        $data = User::AddUser($info);
        if ($data) {
            $message = $request->name . '被增加了';
            \App\Utils\Logs::logInfo($message, Auth::user());
            return response()->success(200, '成功', '新增用户成功');
        } else {
            return response()->fail(100, '失败', '新增用户失败');
        }
    }

//设置项目管理员
    public function SetManage(SetManage $request)
    {
        $data = User::SetManage($request->id, $request->access_code);
        if ($data == null) {
            return response()->fail(100, '修改失败');
        } else if ($data == '-2') {
            return response()->fail(100, '超级管理员不可被修改');
        } else if ($data == '-1') {
            return response()->fail(100, '用户不存在');
        } else if ($data == '-3') {
            return response()->fail(100, '用户不存在');
        } else if ($data == '1') {
            return response()->success(200, '修改成功');
        }
        return response()->fail(100, '修改失败');
    }
}
