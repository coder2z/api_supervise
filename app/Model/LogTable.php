<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogTable extends Model
{
    //定义模型关联的数据表
    protected $table = 'log_tables';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    public $timestamps = true;


    public static function getLogs()
    {
        try {
            return $res = self::all();
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('查询用户信息失败!', [$e->getMessage()]);
            return null;
        }
    }

    /**
     * Get the relationships for the entity.
     *
     * @return array
     */
    public function getQueueableRelations()
    {

    }

}
