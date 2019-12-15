<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Annex extends Model
{
    //定义模型关联的数据表
    protected $table = 'annexes';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    public $timestamps = true;
 
    //根据项目id查找此项目的配置文件 
    public function findConfigById($project_id)
    { 
    	return self::where('project_id',$project_id)->select('path','type')->get()->toArray();
    }
}
