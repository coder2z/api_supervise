<?php

namespace App\Model;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //定义模型关联的数据表
    protected $table = 'projects';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    public $timestamps = true;


    /**
     * Get the relationships for the entity.
     *
     * @return array
     */


    public function getQueueableRelations()
    {
        // TODO: Implement getQueueableRelations() method.
    }
}
