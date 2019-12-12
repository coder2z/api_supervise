<?php

namespace App\Model;

use App\Utils\Logs;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Annex extends Model
{
    //定义模型关联的数据表
    protected $table = 'annexes';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    public $timestamps = true;

    public static function createAnnexes($array = [], $status)
    {
        try {
            if ($status == 'rp') $array['type'] = 1;
            else if ($status == 'word') $array['type'] = 2;
            else $array['type'] = 0;
            return self::insert($array) ? true : false;
        } catch (Exception $e) {
            Logs::logError('添加附件表失败', [$e->getMessage()]);
            return false;
        }
    }

    public static function FindAnnexPath($id, $type)
    {
        try {
            $result = self::where('project_id', $id)->where('type', $type)->first();
            if ($result) {
                $path = $result->path;
                $delete = self::destroy($result->id);
                return ($path && $delete) ? trim(strrchr($path, '/'), '/') : false;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Logs::logError('查找附件表路径失败', [$e->getMessage()]);
            return false;
        }
    }
}
