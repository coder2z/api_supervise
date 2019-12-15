<?php

namespace App\Model;

use App\Utils\Logs;
use Exception;
use Illuminate\Database\Eloquent\Model;

class RequestTable extends Model
{
    //定义模型关联的数据表
    protected $table = 'request_tables';
    //定义主键
    protected $primaryKey = '';
    //定义禁止操作时间
    public $timestamps = true;

    //获取接口的请求表信息
    public static function getInterfaceRequestMsg($interface_id){
        try{
            $result = self::where('interface_id',$interface_id)->select('id','request_mode','params')->get();
            return $result;
        }catch (Exception $e){
            Logs::logError('获取接口的请求表信息失败!', [$e->getMessage()]);
            return null;
        }

    }
}
