<?php

namespace App\Http\Controllers\BackendManager;

use App\Http\Controllers\Controller;
use App\Http\Requests\BackEnd\addAssignmentRequest;
use App\Http\Requests\BackEnd\asignments_idRequest;
use App\Http\Requests\BackEnd\AssignmentRequest;
use App\Http\Requests\BackendManage\getAllAssignmentsRequest;
use App\Model\Assignment;
use App\Utils\Logs;


class TaskManagerController extends Controller
{
    public function getAllAssignments(getAllAssignmentsRequest $request)
    {
        $module_id = $request->get("asignments_id");

        try {
            $all = Assignment::where("module_id",$module_id)->get();

            if ($all != null) {
                return \response()->json([
                    "code" => 200,
                    "msg" => "任务分配获取成功",
                    "data" => $all
                ]);
            } else {
                return \response()->json([
                    "code" => 100,
                    "msg" => "任务分配获取失败",
                    "data" => null
                ]);
            }
        }catch (\Exception $e){
            Logs::logError("任务分配获取异常," . "Exception:".$e->getMessage());
            return \response()->json([
                "code" => 500,
                "msg" => "任务分配获取异常",
                "data" => null
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
