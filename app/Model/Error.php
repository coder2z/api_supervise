<?php

namespace App\Model;

use App\Utils\Logs;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    //定义模型关联的数据表
    protected $table = 'errors';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    public $timestamps = true;

    //获取错误全部错误码
    public static function getStatusCode($project_id){
        try{
            $result = self::where('project_id',$project_id)
                ->select('id','error_code','error_info','http_code')
                ->get();
            return $result;
        } catch (Exception $e){
            Logs::logError('查询错误全部错误码失败!', [$e->getMessage()]);
            return null;
        }

    }
}
