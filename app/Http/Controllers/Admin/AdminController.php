<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Validation\Rule;
use App\Http\Requests\AddUser;

class AdminController extends Controller
{
    //获取某个状态下所有用户(分页)
    public function getUser(Request $request){
            $code=$request->access_code;
            $data=User::getInfo($code,8,array('id','name','access_code','phone_number','email','state'));
            return $data==null ?
                response()->fail(100,'失败'):
                response()->success(200,'成功',$data);
    }

    //删除用户
    public function DeleteUser(Request $request){
        $data=User::DeleteUser($request->ID);
        if($data==null){
            return response()->fail(100,'失败','用户不存在');
        }else{
            return response()->success(200,'删除用户成功');
        }
    }

    //搜索用户(分页)
    public function SearchUser(Request $request){
        $data=User::Search($request->info,8,array('id','name','access_code','phone_number','email','state'));
        return $data==null?
            response()->fail(100,'失败'):
            response()->success(200,'成功',$data);
    }

    //获取指定用户信息
    public function ShowUserInfo(Request $request){
        $array=['id','name','access_code','phone_number','email','state'];
        $data=User::ShowUserInfo($request->ID,$array);
        if(isset($data[0]->id)){
            return response()->success(200,'成功',$data);
        }else{
            return response()->fail(100,'失败','用户不存在');
        }
    }

    //修改用户
    public function UpdateUserInfo(Request $request){
        $validator=Validator::make($request->all(),[
            'name'          =>'required|string|max:5',
            'password'      =>'check_password',
            'access_code'   =>'check_code',
            'state'         =>'check_state',
            'phone'         =>['required','digits:11',Rule::unique('users','phone_number')->ignore($request->ID)],
            'email'         =>['required','email',Rule::unique('users','email')->ignore($request->ID)],
        ]);
        if($validator->fails()){
            throw (new HttpResponseException(response()->fail(422, '参数错误！', $validator->errors()->all(), 422)));
        }
        if(!isset(User::find($request->ID)->id)){
            return response()->fail(100,'失败','用户不存在');
        }
     $info=[$request->ID,$request->name,$request->password,$request->phone,$request->email,$request->access_code,$request->state];
    $data=User::UpdateUserInfo($info);
    if(!$data){
        return response()->fail(100,'失败','修改失败');
    }else{
        $message=$request->name+'被修改了';
        \App\Utils\Logs::logInfo($message,Auth::user());
        return response()->success(200,'成功','修改成功');
    }
    }

    //新建用户
    public function AddUser(AddUser $request){
        $info=[$request->name,$request->password,$request->phone,$request->email,$request->access_code];
        $data=User::AddUser($info);
        if($data){
            $message=$request->name+'被增加了';
            \App\Utils\Logs::logInfo($message,Auth::user());
            return response()->success(200,'成功','新增用户成功');
        }else{
            return response()->fail(100,'失败','新增用户失败');
        }
    }
}
