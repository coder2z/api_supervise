<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InterfaceTable extends Model
{
    //定义模型关联的数据表
    protected $table = 'interface_tables';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
<<<<<<< HEAD
    public $timestamps = false;
//    protected $dispatchesEvents=[
//        'saved' => UserSaved::class,
//        'deleted' => UserDeleted::class,
//    ];
=======
    public $timestamps = true;
>>>>>>> d53a4e25a6c04ca0a85a60586a01e6d74b522fd1
}
