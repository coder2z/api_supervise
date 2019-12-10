<?php

namespace App\Http\Controllers\ProjectAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectAdmin\Project\getProjectRequest;
use App\Model\Annex;
use App\Model\Project;
use App\Model\User;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function getAllProject()
    {
        $projectInfo = Project::getAllProjectInfo();
        return $projectInfo != null ? response()->success(200, '获取信息成功', $projectInfo) : response()->fail(100, '获取信息失败', $projectInfo);
    }

    public function getProject($id)
    {
        $projectInfo = Project::getProjectInfo($id);
        return $projectInfo != null ? response()->success(200, '获取信息成功', $projectInfo) : response()->fail(100, '获取信息失败', $projectInfo);
    }

    public function setProject(getProjectRequest $request, $id)
    {
        $adminid = User::find(1);
        // $adminid = Auth::id();
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
        // dd(123);
        if (!self::upload($request->PrototyMap, 'rp', $id))
            return response()->fail(100, 'rp文件有问题  ', null);
        return response()->success(200, '更新成功', null);
    }

    public function addProject(getProjectRequest $request)
    {
        $adminid = User::find(1);
        // $adminid = Auth::id();
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
        return response()->success(200, '添加成功', null);
    }

    public function deleteProject($id)
    {
        if ($word = Annex::FindAnnexPath($id, 2))
            if (!self::deleteAnnex($word, 'word'))
                return response()->fail(100, '删除word文件失败', null);
        if ($rp = Annex::FindAnnexPath($id, 1))
            if (!self::deleteAnnex($rp, 'rp'))
                return response()->fail(100, '删除rp文件失败', null);
        $status = Project::destroyProject($id);
        return $status ? response()->success(200, '删除成功', null) : response()->fail(100, '删除失败', null);
    }

    private function upload($file, $status, $project_id)
    {
        // dd($file);
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
            $fileprop['created_at'] = date('Y-m-d H:i:s');
            $fileprop['updated_at'] = date('Y-m-d H:i:s');
            return Annex::createAnnexes($fileprop, $status);
        }
        return false;
    }

    private function CheckAdminId($id)
    {
        return $id->access_code == -1 ? true : false;
    }

    private function projectHandle($request)
    {
        $projectinfo['name'] = $request->ProjectName;
        $projectinfo['discribe'] = $request->ProjectDescription;
        $projectinfo['amdin_user_id'] = User::find(1)->id;
        // $projectinfo['amdin_user_id'] = Auth::id();
        $projectinfo['pre_url'] = 'null';
        $projectinfo['created_at'] = date('Y-m-d H:i:s');
        $projectinfo['updated_at'] = date('Y-m-d H:i:s');
        return $projectinfo;
    }

    private function deleteAnnex($filename, $status)
    {
        return Storage::disk($status)->delete($filename);
    }
}
