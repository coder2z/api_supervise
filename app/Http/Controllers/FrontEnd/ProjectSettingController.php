<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Annex;
use App\Model\Project;

class ProjectSettingController extends Controller
{
    //得到项目的配置文件及项目地址
    public function getConfigFile(Request $request)
    {
        $project_id = $request->project_id;
        $data = [];
        $annex = new Annex();
        $annex_data = $annex->findConfigById($project_id);
        $project = new Project();
        $github_url = $project->findProjectURL($project_id);
        foreach ($annex_data as $val) {
            if (1 == $val['type'])
                $data[] = ['map_path' => $val['path']];
            if (2 == $val['type'])
                $data[] = ['file_path' => $val['path']];
            if (0 == $val['type'])
                $data[] = ['sql_path' => $val['path']];
        }
        $data[] = $github_url;
        return response()->success(200, '成功!', $data);
    }

    //下载配置文件
    public function downloadConfigFile(Request $request)
    {
        $path = $request->file_path;
        return response()->download($path);
    }
}
