<?php

namespace App\Http\Controllers\ProjectAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectAdmin\Project\getProjectRequest;
use App\Model\Annex;
use App\Model\Project;
use App\Model\User;
use App\Utils\Logs;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * @return mixed
     * @throws Exception
     */
    public function getAllProject()
    {
        $projectInfo = Project::getMeAllProjectInfo();
        return $projectInfo !== null ?
            response()->success(200, '获取全部信息成功', $projectInfo) :
            response()->fail(100, '获取全部信息失败');
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function getProject($id)
    {
        if ($id <= 0) {
            Logs::logError('传入id值小于零');
            return response()->fail(100, '传入id值小于零', null);
        }
        $projectInfo = Project::getProjectInfo($id);
        if ($projectInfo !== null) {
            Logs::logInfo('获取信息成功');
            return response()->success(200, '获取信息成功', $projectInfo);
        } else {
            Logs::logError('获取信息失败');
            return response()->fail(100, '获取信息失败');
        }
    }

    /**
     * @param getProjectRequest $request
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function setProject(getProjectRequest $request, $id)
    {
        if ($id <= 0) {
            Logs::logError('传入id值小于零');
            return response()->fail(100, '传入id值小于零', null);
        }
        $adminid = Auth::id();
        if (!self::CheckAdminId($adminid)) {
            return response()->fail(100, '用户权限不够', null);
        }
        $setpro = Project::updateProject(self::projectHandle($request), $id);
        if (!$setpro) return response()->fail(100, '更新项目失败', null);

        if ($word = Annex::FindAnnexPath($id, 2))
            if (!self::deleteAnnex($word, 'word'))
                return response()->fail(100, '删除word文件失败', null);
        if ($rp = Annex::FindAnnexPath($id, 1))
            if (!self::deleteAnnex($rp, 'rp'))
                return response()->fail(100, '删除rp文件失败', null);

        if (!self::upload($request->RequirementDocument, 'word', $id))
            return response()->fail(100, 'word文件有问题', null);
        if (!self::upload($request->PrototyMap, 'rp', $id))
            return response()->fail(100, 'rp文件有问题  ', null);
        Logs::logInfo('更新项目成功');
        return response()->success(200, '更新成功', null);
    }

    /**
     * @param getProjectRequest $request
     * @return mixed
     * @throws Exception
     */
    public function addProject(getProjectRequest $request)
    {
        $adminid = Auth::id();
        if (!self::CheckAdminId($adminid)) {
            return response()->fail(100, '用户权限不够', null);
        }
        $project = Project::createProject(self::projectHandle($request));
        if (!$project) {
            return response()->fail(100, '添加项目失败', null);
        }
        if (!self::upload($request->RequirementDocument, 'word', $project->id))
            return response()->fail(100, 'word文件有问题', null);
        if (!self::upload($request->PrototyMap, 'rp', $project->id))
            return response()->fail(100, 'rp文件有问题  ', null);
        Logs::logInfo('添加项目成功');
        return response()->success(200, '添加成功', null);
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function deleteProject($id)
    {
        if ($id <= 0) {
            Logs::logError('传入id值小于零');
            return response()->fail(100, '传入id值小于零');
        }
        try {
            if ($word = Annex::FindAnnexPath($id, 2))
                if (!self::deleteAnnex($word, 'word'))
                    return response()->fail(100, '删除word文件失败');
        } catch (Exception $e) {
            Logs::logError('删除word文件失败', [$e->getMessage()]);
            return response()->fail(100, '删除word文件失败');
        }
        try {
            if ($rp = Annex::FindAnnexPath($id, 1))
                if (!self::deleteAnnex($rp, 'rp'))
                    return response()->fail(100, '删除rp文件失败');
        } catch (Exception $e) {
            Logs::logError('删除rp文件失败', [$e->getMessage()]);
            return response()->fail(100, '删除rp文件失败');
        }
        $status = Project::destroyProject($id);
        if ($status) {
            Logs::logInfo('删除项目成功');
            return response()->success(200, '删除成功');
        } else {
            Logs::logError('删除项目失败');
            return response()->fail(100, '删除失败');
        }
    }

    /**
     * @param $file
     * @param $status
     * @param $project_id
     * @return bool
     * @throws Exception
     */
    private function upload($file, $status, $project_id)
    {
        try {
            //解析当前文档是否有效
            if ($file->isValid()) {
                //文件后缀名
                $ext = $file->getClientOriginalExtension();
                if ($status == 'rp' && $status != $ext) return false;
                if ($status == 'word' && ($ext != 'doc' && $ext != 'docx')) return false;
                //文件原本的名字
                $name = $file->getClientOriginalName();
                $name = substr($name, 0, strpos($name, '.'));
                //临时绝对路径
                $path = $file->getRealPath();
                //文件名
                $filename = $name . date('Y-m-d~h-i-s') . '.' . $ext;
                Storage::disk($status)->put($filename, \file_get_contents($path));
                //附件信息
                $fileprop['project_id'] = $project_id;
                $filepath = "/storage/app/public/{$status}/{$filename}";
                $fileprop['path'] = $filepath;
                return Annex::createAnnexes($fileprop, $status);
            }
        } catch (Exception $e) {
            Logs::logError('上传文件失败!', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    private function CheckAdminId($id)
    {
        return $id->access_code == -1 ? true : false;
    }

    /**
     * @param $request
     * @return mixed
     */
    private function projectHandle($request)
    {
        $projectinfo['name'] = $request->ProjectName;
        $projectinfo['discribe'] = $request->ProjectDescription;
        $projectinfo['amdin_user_id'] = Auth::id();
        $projectinfo['pre_url'] = 'null';
        return $projectinfo;
    }

    /**
     * @param $filename
     * @param $status
     * @return bool
     * @throws Exception
     */
    private function deleteAnnex($filename, $status)
    {
        try {
            return Storage::disk($status)->delete($filename);
        } catch (Exception $e) {
            Logs::logError('删除附件文件失败!', [$e->getMessage()]);
            return false;
        }
    }
}
