<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;


class Error extends Model
{
    //定义模型关联的数据表
    protected $table = 'errors';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    public $timestamps = true;

    protected $guarded = [];

    //2.错误码设置
    //查询错误码
    public static function selectErrorCodeMethod()
    {
        try {
            return Error::paginate(env('PAGE_NUM'), ['id','error_code', 'error_info', 'http_code','project_id']);
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('查询错误码失败!', [$e->getMessage()]);
            return null;
        }
    }

    //新增错误码
    public static function addErrorCodeMethod($input)
    {
        try {
            //开启事务
            DB::beginTransaction();
            $rs = Error::create($input);
            DB::commit();
            return $rs;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('新增错误码失败!', [$e->getMessage()]);
            DB::rollback();
            return null;
        }
    }

    //查询单个错误码信息
    public static function oneSelectErrorCodeMethod($m_id){
        try{
            return $rs = Error::find($m_id,['id','project_id', 'error_code', 'error_info','http_code']);
        }catch (\Exception $e){
            \App\Utils\Logs::logError('查询单个错误码信息失败!', [$e->getMessage()]);
            return null;
        }
    }
    //修改错误码
    public static function editErrorCodeMethod($input, int $e_id)
    {
        try {
            //开启事务
            DB::beginTransaction();
            $rs = Error::where('id', $e_id)->update($input);
            DB::commit();
            return $rs;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('修改错误码失败!', [$e->getMessage()]);
            DB::rollback();
            return null;
        }
    }

    //删除错误码
    public static function deErrorCodeMethod($e_id)
    {
        try {
            //开启事务
            DB::beginTransaction();
            ErrorRelation::where('error_id',$e_id)->delete();
            $rs = Error::where('id', $e_id)->delete();
            DB::commit();
            return $rs;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('删除错误码信息失败!', [$e->getMessage()]);
            DB::rollback();
            return null;
        }
    }

    //获取错误全部错误码（单用）
    public static function getStatusCode($project_id)
    {
        try {
            $result = self::where('project_id', $project_id)
                ->select('id', 'error_code', 'error_info', 'http_code')
                ->get();
            return $result;
        } catch (Exception $e) {
            Logs::logError('查询错误全部错误码失败!', [$e->getMessage()]);
            return response()->fail(100, '查询错误全部错误码失败，请重试!', null);
        }
    }
}
