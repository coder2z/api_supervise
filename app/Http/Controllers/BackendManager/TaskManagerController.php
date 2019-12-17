<?php

namespace App\Http\Controllers\BackendManager;

use App\Http\Controllers\Controller;
use App\Http\Requests\BackEnd\addAssignmentRequest;
use App\Http\Requests\BackEnd\asignments_idRequest;
use App\Http\Requests\BackEnd\AssignmentRequest;
use App\Http\Requests\BackendManage\getAllAssignmentsRequest;
use App\Model\Assignment;
use App\Model\ProjectModule;
use App\Utils\Logs;


class TaskManagerController extends Controller
{
    public function getTwoPm(getAllAssignmentsRequest $request){


        try {
            $project_id = $request->get("project_id");
            $PModule =ProjectModule::where("project_id",$project_id)->get(["class_name","id"]);
            $PMember = ProjectMember::where("project_id",$project_id)->get(["user_id"]);


            $tmp1 = null;
            for ($i = 0;$i<count($PMember);$i++){
                $tmp1[$i]["name"] = json_decode(User::where("id", $PMember[$i]->user_id)->get(["name"]), true)[0]["name"];
                $tmp1[$i]["user_id"] = json_decode($PMember[$i]->user_id);
            }
            $tmp = null;
            $tmp=[
                "allProjectModule"=>$PModule,
                "allProjectMember"=>$tmp1
            ];
            return \response()->json([
                "code" => 200,
                "msg" => "获取项目成员和模块成功",
                "data" => $tmp
            ]);

        }catch (\Exception $exception){
            Logs::logError("获取项目成员和模块异常," . "Exception:" . $exception->getMessage());
            return \response()->json([
                "code" => 500,
                "msg" => "获取项目成员和模块异常",
                "data" => null
            ]);
        }
    }
    public function getAllAssignments(getAllAssignmentsRequest $request)
    {

        try {

            $project_id = $request->get("project_id");

            $all = ProjectModule::where("project_id", $project_id)->rightJoin('assignments', 'assignments.module_id',
                '=', 'project_modules.id')->get();

            $tmp = null;
            for ($i = 0; $i < count($all); $i++) {
                $tmp[$i]["module_id"] = $all[$i]->module_id;
                $tmp[$i]["user_id"] = $all[$i]->user_id;
                $tmp[$i]["class_name"] = $all[$i]->class_name;
                $tmp[$i]["name "] = json_decode(User::where("id", $all[$i]->user_id)->get(["name"]), true)[0]["name"];
            }

            if ($tmp != null) {
                return \response()->json([
                    "code" => 200,
                    "msg" => "任务分配获取成功",
                    "data" => $tmp
                ]);
            } else {
                return \response()->json([
                    "code" => 100,
                    "msg" => "任务分配获取失败",
                    "data" => null
                ]);
            }
        } catch (\Exception $e) {
            Logs::logError("任务分配获取异常," . "Exception:" . $e->getMessage());
            return \response()->json([
                "code" => 500,
                "msg" => "任务分配获取异常",
                "data" => []
            ]);
        }
    }

    public function addAssignment(addAssignmentRequest $request)
    {
        try {
            $develop_member = $request->get("user_id");
            $module_id = $request->get("module_id");

            $ass = new Assignment();
            $ass->user_id = $develop_member;
            $ass->module_id = $module_id;

            if ($ass->save()) {
                return \response()->json([
                    "code" => 200,
                    "msg" => "任务分配添加成功",
                    "data" => null
                ]);
            } else {
                return \response()->json([
                    "code" => 100,
                    "msg" => "任务分配添加失败",
                    "data" => null

                ]);
            }
        }catch (\Exception $e){
            Logs::logError("任务分配添加异常," . "Exception:".$e->getMessage());
            return \response()->json([
                "code" => 500,
                "msg" => "任务分配添加异常",
                "data" => null
            ]);
        }
    }

    public function updateAssignment(AssignmentRequest $request)
    {
        try {
            $asignments_id = $request->get("asignments_id");
            $develop_member = $request->get("user_id");
            $module_id = $request->get("module_id");

            $ass = Assignment::find($asignments_id);
            $ass->user_id = $develop_member;
            $ass->module_id = $module_id;
            if ($ass->save()) {
                return \response()->json([
                    "code" => 200,
                    "msg" => "任务分配更新成功",
                    "data" => null
                ]);
            } else {
                return \response()->json([
                    "code" => 100,
                    "msg" => "任务分配更新失败",
                    "data" => null
                ]);
            }
        }catch (\Exception $e){
            Logs::logError("任务分配更新异常," . "Exception:".$e->getMessage());
            return \response()->json([
                "code" => 500,
                "msg" => "任务分配更新异常",
                "data" => null
            ]);
        }
    }

    public function deleteAssignment(asignments_idRequest $request)
    {

        try {
            $deleteID = $request->get("asignments_id");

            $isDelete = Assignment::find($deleteID);

            if ($isDelete->delete()) {
                return \response()->json([
                    "code" => 200,
                    "msg" => "任务分配删除成功",
                    "data" => null
                ]);
            } else {
                return \response()->json([
                    "code" => 100,
                    "msg" => "任务分配删除失败",
                    "data" => null
                ]);
            }
        }catch (\Exception $e){
            Logs::logError("任务分配删除异常," . "Exception:".$e->getMessage());
            return \response()->json([
                "code" => 500,
                "msg" => "任务分配删除异常",
                "data" => null
            ]);
        }
    }
}
