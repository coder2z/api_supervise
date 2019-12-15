<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Assignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\In;
use App\Model\User;
use App\Events\Event;


class InterfaceTable extends Model
{
    //定义模型关联的数据表
    protected $table = 'interface_tables';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    public $timestamps = true;


    //获取所有接口
    public static function get_API_all($pages){
        try{
            $data=InterfaceTable::join('assignments as a','interface_tables.module_id','=','a.module_id')
                ->join('users as u','u.id','=','a.user_id')
                ->select('interface_tables.id','interface_tables.interface_name','u.name','interface_tables.state')
                ->paginate($pages);
            return $data;
        }catch (\Exception $e){
            \App\Utils\Logs::logError('获取接口数据失败!', [$e->getMessage()]);
            return null;
        }
    }

    //根据ID获取指定接口的所有信息
    public static function get_API_info($id){
        try{
            $find=InterfaceTable::find($id);
            if($find==null){
                return -1;
            }
            $error_code=InterfaceTable::join('error_relations as er','er.interface_id','=','interface_tables.id')
                ->join('errors as e','e.id','=','er.error_id')->select('e.error_code')->where('interface_tables.id',$id)->get();
            $interface=InterfaceTable::join('request_tables as rt','rt.interface_id','=','interface_tables.id')
                ->join('response_tables as rs','rs.interface_id','=','interface_tables.id')
                ->select('interface_tables.id','interface_name','request_mode','module_id','route_path','response_data_type'
                    ,'interface_discribe','header','params')->where('interface_tables.id',$id)->first();
            $interface_response_success_example=InterfaceTable::join('response_tables as rs','rs.interface_id','=','interface_tables.id')
                ->select('rs.response_data')->where('interface_tables.id',$id)->where('rs.state','1')->first();
            $interface_response_fail_example=InterfaceTable::join('response_tables as rs','rs.interface_id','=','interface_tables.id')
                ->select('rs.response_data')->where('interface_tables.id',$id)->where('rs.state','0')->first();
            $data['interface_id']=$interface['id'];
            $data['interface_name']=$interface['interface_name'];
            $data['interface_method']=$interface['request_mode'];
            $data['interface_url']=$interface['route_path'];
            $data['interface_module_name']=$interface['module_id'];
            $data['interface_response_type']=$interface['response_data_type'];
            $data['interface_response_error_code']=$error_code;
            $data['interface_intro']=$interface['interface_discribe'];
            $data['interface_header']=json_decode($interface['header']);
            $data['interface_request_type']=json_decode($interface['params'])->interface_request_type;
            $data['interface_request']=json_decode($interface['params'])->interface_request;
            $data['interface_response_success_example']=$interface_response_success_example;
            $data['interface_response_fail_example']=$interface_response_fail_example;
            return $data;
        }catch (\Exception $e){
            \App\Utils\Logs::logError('查询指定接口数据失败!', [$e->getMessage()]);
            return null;
        }

    }

    //修改指定接口审核状态
    public static function checkAPI($id,$state){
        try{
            $model=InterfaceTable::find($id);
            if($model==null){
                return 0;
            }
            $model->state=$state;
            $data=$model->save();
            return $data;
        }catch (\Exception $e){
            \App\Utils\Logs::logError('修改接口状态失败!', [$e->getMessage()]);
            return null;
        }
    }

    //获取所有接口分配
    public static function getAllInterface($pages){
        try{
            $data=InterfaceTable::join('project_modules as pm','pm.id','=','interface_tables.module_id')
                ->join('assignments as as','as.module_id','=','pm.id')->join('users as us','us.id','=','as.user_id')
                ->select('interface_tables.id','us.name as developer_name','pm.modules_name as module_name','class_name','full_class_name')
                ->paginate($pages);
            return $data;
        }catch (\Exception $e){
            \App\Utils\Logs::logError('查询接口失败!', [$e->getMessage()]);
            return null;
        }
    }

    //接口分配情况筛选
    public static function screenInterface($develop_id,$module_id,$pages){
       try{
           $user=user::find($develop_id);
           if($user==null&&$develop_id!=null){
               return -1;
           }
           $module=ProjectModule::find($module_id);
           if($module==null&&$module_id!=null){
               return -2;
           }
           $rel=Assignment::where('user_id',$develop_id)->where('module_id',$module_id)->select('id')->first();
           if($rel==null&&$develop_id!=null&&$module_id!=null){
               return -3;
           }
           $temp=InterfaceTable::join('project_modules as pm','pm.id','=','interface_tables.module_id')
               ->join('assignments as as','as.module_id','=','pm.id')->join('users as us','us.id','=','as.user_id')
               ->select('interface_tables.id','us.name as developer_name','pm.modules_name as module_name','class_name','full_class_name');
           if($develop_id==null&&$module_id==null){
               $data=$temp->paginate($pages);
           }else if($develop_id==null&&$module_id!=null){
               $data=$temp->where('pm.id','=',$module_id)->paginate($pages);
           }else if($develop_id!=null&&$module_id==null){
               $data=$temp->where('us.id','=',$develop_id)->paginate($pages);
           }else{
               $data=$temp->where('us.id','=',$develop_id)->where('pm.id','=',$module_id)->paginate($pages);
           }
           return $data;
        }catch (\Exception $e){
            \App\Utils\Logs::logError('查询接口失败!', [$e->getMessage()]);
            return null;
        }
    }
    public function getQueueableRelations()
    {
        // TODO: Implement getQueueableRelations() method.
    }
}


