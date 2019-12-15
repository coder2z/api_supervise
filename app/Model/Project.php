<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //定义模型关联的数据表
    protected $table = 'projects';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    public $timestamps = true;

    public function getAllProjects($id){
        try{
            $access = Project::leftjoin("users as user","projects.amdin_user_id","=","user.id")
            ->leftjoin("positions as position","position.user_id","user.id")
            ->select("projects.id","projects.name","projects.discribe","position.position_code")
            ->get();
            return $access;
        }catch (\Exception $e){
            \App\Utils\Logs::logError('获取所有项目失败!', [$e->getMessage()]);
            return null;
        }
        
    }

    //根据项目id查询项目的github地址
    public function findProjectURL($project_id)
    {
    	return self::where('id',$project_id)->select('pre_url as github_url')->get()->toArray()[0];
    }
}
