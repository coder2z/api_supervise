<?php

namespace App\Model;


use Illuminate\Contracts\Auth\Guard;
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
    /**
     * @return |null
     * @throws Exception
     */
    public static function getMeAllProjectInfo()
    {
        try {
            return self::orderBy('updated_at', 'desc')
                ->select('id', 'name', 'discribe')
                ->where('amdin_user_id', auth()->id())
                ->paginate(env('PAGE_NUM'));
        } catch (Exception $e) {
            Logs::logError('查询全部项目信息失败！', [$e->getMessage()]);
            return null;
        }
    }

    /**
     * @param $id
     * @return |null
     * @throws Exception
     */
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


    /**
     * @param array $array
     * @return Project|bool
     * @throws Exception
     */
    public static function createProject($array = [])
    {
        try {
            $project = new self;
            $project->name = $array['name'];
            $project->discribe = $array['discribe'];
            $project->amdin_user_id = $array['amdin_user_id'];
            $project->pre_url = $array['pre_url'];
            $result = $project->save();
            return $result ? $project : false;
        } catch (Exception $e) {
            Logs::logError('创建项目失败！', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * @param array $array
     * @param $id
     * @return bool
     * @throws Exception
     */
    public static function updateProject($array = [], $id)
    {
        try {
            $project = self::find($id);
            $project->name = $array['name'];
            $project->discribe = $array['discribe'];
            $project->amdin_user_id = $array['amdin_user_id'];
            $project->pre_url = $array['pre_url'];
            $result = $project->save();
            return $result ? true : false;
        } catch (Exception $e) {
            Logs::logError('更新项目失败！', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
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

    //获取项目信息(单独用)
    public static function findProjectMsg($project_id){
        try{
            $result = self::where('id',$project_id)->select('id','name','discribe')->get();
            return $result;
        } catch (Exception $e){
            Logs::logError('查询项目信息失败!', [$e->getMessage()]);
            return null;
        }

    }

}
