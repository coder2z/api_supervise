<?php

namespace App\Model;

use App\Utils\Logs;
use Exception;
use Illuminate\Database\Eloquent\Model;

class ResponseTable extends Model
{
    //定义模型关联的数据表
    protected $table = 'response_tables';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    public $timestamps = true;

    //获取接口的响应表信息(单用)
    public static function getInterResposeMsg($interface_id,$stat){
        try{
            $result = self::where('interface_id',$interface_id)
                ->where('state',$stat)
                ->select('response_data_type','response_data')
                ->get();
            return $result;
        }
        catch (Exception $e){
            Logs::logError('获取接口的响应表信息失败!', [$e->getMessage()]);
            return response()->fail(100, '获取接口的响应表信息失败，请重试!', null);
        }
    }
}
