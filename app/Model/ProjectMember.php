<?php

namespace App\Model;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    //定义模型关联的数据表
    protected $table = 'project_members';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    public $timestamps = true;

    /**
     * @param $name
     * @param $argument
     * @param array $array
     * @return |null
     * @throws \Exception
     */
    public static function get_Info($name, $argument, $array = [])//获取project_id相关信息
    {
        try {
            return $array == null ?
                self::where($name, $argument)->get() :
                self::where($name, $argument)->get($array);
        } catch (\Exception $exception) {
            Logs::logError('查询项目成员表错误!', [$exception->getMessage()]);
            return null;
        }
    }

    public static function addMembers($userid=[],$itemid)
    {
        try {
            foreach ($userid as $key => $u_id){
                if(self::where('project_id',(int)$itemid)->where('user_id',(int)$u_id)->first()){
                    continue;
                }
                $user = User::where('id',(int)$u_id)->first();
                $Puser = new ProjectMember();
                $Puser -> project_id = $itemid;
                $Puser -> user_id = $user -> id;
                $Puser -> type = 0;
                $Puser -> save();
            }
            return true;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('添加用户失败!', [$e->getMessage()]);
            return false;
        }
    }
}
