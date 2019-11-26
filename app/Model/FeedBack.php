<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Exception;

class FeedBack extends Model
{
    //定义模型关联的数据表
    protected $table = 'feed_backs';
    //定义主键
    protected $primaryKey = '';
    //定义禁止操作时间
    public $timestamps = false;
    public static function getInfo_echo($id)
    {
        $data=array();$i=0;
        $info=FeedBack::where("to_user_id",$id)->get();
        foreach ($info as $item) {
            $user=User::find($item->from_uesr_id);
            if($user==null)$user["name"]="用户已注销";
            $json_decode=json_decode($item->content);
              $data[$i++]=array(
                  "name"=>$json_decode->name,
                  "type"=>$json_decode->type,
                  "from"=>$user["name"],
                  "to"=>"我",
                  "update_at"=>$item->updated_at,
                  "create_at"=>$item->created_at
              );
        }
            return $data;
    }
}
