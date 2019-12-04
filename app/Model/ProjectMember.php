<?php

namespace App\Model;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Exception;
use function PHPSTORM_META\type;

class ProjectMember extends Model
{
    //定义模型关联的数据表
    protected $table = 'project_members';
    //定义主键
    protected $primaryKey = '';
    //定义禁止操作时间
    public $timestamps = false;

    public static function get_Info($project_id ,$array = [])
    {
        try
        {
        return $array==null ?  self::where("project_id",$project_id)->get():self::where("project_id",$project_id)->get($array);
        }
        catch (Exception $exception)
        {
            Logs::logError('查询项目成员表错误!', [$exception->getMessage()]);
            return null;
        }
    }
}
