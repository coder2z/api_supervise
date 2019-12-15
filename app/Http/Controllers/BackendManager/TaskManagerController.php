<?php

namespace App\Http\Controllers\BackendManager;

use App\Http\Controllers\Controller;
use App\Http\Requests\BackendManage\AssignmentRequest;
use App\Model\Assignment;
use Illuminate\Http\Request;


class TaskManagerController extends Controller
{
    //
    public function getAllAssignments()
    {
        $all = Assignment::all();
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
    }

    public function addAssignment(AssignmentRequest $request)
    {
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
    }

    public function updateAssignment(AssignmentRequest $request)
    {
        $asignments_id = $request->get("asignments_id");
        if ($asignments_id != null) {
            $develop_member = $request->get("user_id");
            $module_id = $request->get("module_id");
            $ass = Assignment::find($asignments_id);
            $ass->user_id = $develop_member;
            $ass->module_id = $module_id;
            $ass->updated_at = date('Y-m-d h:i:s', time());
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
        } else {
            return \response()->json([
                "code" => 422,
                "msg" => "参数错误！",
                "data" => [
                    'asignments_id 不能为空'
                ]
            ], 422);
        }
    }

    public function deleteAssignment(Request $request)
    {


        $deleteID = $request->get("asignments_id");

        if ($deleteID != null) {
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
        } else {
            return \response()->json([
                "code" => 100,
                "msg" => '参数错误！',
                "data" => [
                    'asignments_id 不能为空'
                ]
            ]);
        }

    }
}
