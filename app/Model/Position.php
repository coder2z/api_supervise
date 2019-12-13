<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    //定义模型关联的数据表
    protected $table = 'positions';
    //定义主键
    protected $primaryKey = '';
    //定义禁止操作时间


    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
        'deleted' => UserDeleted::class,
    ];
    public $timestamps = true;
    /**
     * 设置批量赋值
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * @param $userID
     */
    public static function checkPosition($userID)
    {
        $positions = array();
        $position = self::where('user_id', $userID)->get(['position_code']);
        foreach ($position as $item) {
            $positions[] = $item->position_code;
        };
        return $positions;
    }
}
