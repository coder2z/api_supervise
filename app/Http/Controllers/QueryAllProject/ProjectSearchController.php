<?php

namespace App\Http\Controllers\QueryAllProject;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Project;

class ProjectSearchController extends Controller
{
    //获取所有项目
    public function getAllProject(Request $request)
    {
        $id = Auth()->id();
        $project = new Project();
        $data = $project->getAllProjects($id);
        return count($data) > 0 ?
            response()->success(200, '获取项目成功!', $data) :
            response()->fail(100, '获取项目失败!');

    }
}
