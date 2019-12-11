<?php

namespace App\Model;

use App\Utils\Logs;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    //定义模型关联的数据表
    protected $table = 'projects';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    public $timestamps = false;

    public static function getAllProjectInfo()
    {
        try {
            return self::orderBy('updated_at', 'desc')->select('name', 'discribe')->paginate(12);
        } catch (Exception $e) {
            Logs::logError('查询全部项目信息失败！', [$e->getMessage()]);
            return null;
        }
    }

    public static function getProjectInfo($id)
    {
        try {
            $result = self::where('id', $id)->select('id', 'name', 'discribe')->first();
            $word = Annex::where('project_id', $id)->where('type', 2)->select('path')->first()->path;
            $rp = Annex::where('project_id', $id)->where('type', 1)->select('path')->first()->path;
            $result['word_path'] = trim(strrchr($word, '/'), '/');
            $result['rp_path'] = trim(strrchr($rp, '/'), '/');
            return $result;
        } catch (Exception $e) {
            Logs::logError('查询项目信息失败！', [$e->getMessage()]);
            return null;
        }
    }

    public static function createProject($array = [])
    {
        try {
            $project = new self;
            $project->name = $array['name'];
            $project->discribe = $array['discribe'];
            $project->amdin_user_id = $array['amdin_user_id'];
            $project->pre_url = $array['pre_url'];
            $project->created_at = $array['created_at'];
            $project->updated_at = $array['updated_at'];
            $result = $project->save();
            return $result ? $project : false;
        } catch (Exception $e) {
            Logs::logError('创建项目失败！', [$e->getMessage()]);
            return false;
        }
    }

    public static function updateProject($array = [], $id)
    {
        try {
            $project = self::find($id);
            $project->name = $array['name'];
            $project->discribe = $array['discribe'];
            $project->amdin_user_id = $array['amdin_user_id'];
            $project->pre_url = $array['pre_url'];
            $project->updated_at = $array['updated_at'];
            $result = $project->save();
            return $result ? true : false;
        } catch (Exception $e) {
            Logs::logError('更新项目失败！', [$e->getMessage()]);
            return false;
        }
    }

    public static function destroyProject($id)
    {
        try {
            if (!self::find($id)) return false;
            return self::destroy($id) ? true : false;
        } catch (Exception $e) {
            Logs::logError('删除项目失败!', [$e->getMessage()]);
            return false;
        }
    }
}
