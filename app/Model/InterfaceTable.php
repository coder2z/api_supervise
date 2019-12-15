<?php

namespace App\Model;

use App\Utils\Logs;
use Exception;
use Illuminate\Database\Eloquent\Model;
use App\Events\Event;

class InterfaceTable extends Model
{
    //定义模型关联的数据表
    protected $table = 'interface_tables';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    public $timestamps = true;

    public function getQueueableRelations()
    {
        // TODO: Implement getQueueableRelations() method.
    }

    //获取模型对应的所有方法名（单用）
    public static function findModulesAllMet($module_id){
        try{
            $result = self::where('module_id',$module_id)
                ->where('state',1)
                ->select('id as interface_id','function_name')->get();
            return $result;
        }catch (Exception $e){
            Logs::logError('查询模型对应的所有方法名!', [$e->getMessage()]);
            return null;
        }

    }

    //获取接口详情(单用)
    public static function getInfterfaceInfo($interface_id){
        try{
            $result = self::where('id',$interface_id)->select('id','interface_name','function_name','route_path')
                ->where('state',1)
                ->get();
            return $result;
        }
        catch (Exception $e){
            Logs::logError('获取接口详情失败!', [$e->getMessage()]);
            return null;
        }
    }
}


