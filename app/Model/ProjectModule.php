<?php

namespace App\Model;

use App\Utils\Logs;
use Exception;
use Illuminate\Database\Eloquent\Model;

class ProjectModule extends Model
{
    //定义模型关联的数据表
    protected $table = 'project_modules';
    //定义主键
    protected $primaryKey = '';
    //定义禁止操作时间
    public $timestamps = true;

    //获取所有的模型名(单用)
    public static function findModules($project_id){
        try{
            $result = self::where('project_id',$project_id)
                ->select('id','project_id','modules_name','class_name','full_class_name','utility')
                ->get();
            return $result;
        } catch (Exception $e){
            Logs::logError('获取所有模型信息失败!', [$e->getMessage()]);
            return null;
        }
    }
}
