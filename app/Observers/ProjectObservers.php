<?php

namespace App\Observers;

use App\Model\Project;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use App\Model\LogTable;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class ProjectObservers
{
    public function created(Project $project)
    {
        try {
            $id = Auth::id();
            $data = Project::where('amdin_user_id', $id)->orderby('id', 'desc')->first();
            $user = User::where('id', $id)->first();

            $res = new LogTable();
            $res->user_id = $id;
            $res->operation_type = '增加';
            $res->operation_object = $data->name;
            $res->content = $user->name . '添加了' . $data->name . '项目';
            $res->created_at = $data->created_at;
            $res->save();

        } catch (\Exception $e) {

        }
    }

    public function updated()
    {
        try {
            $id = Auth::id();
            $data = Project::where('amdin_user_id', $id)->orderby('id', 'desc')->first();
            $user = User::where('id', $id)->first();

            $res = new LogTable();
            $res->user_id = $id;
            $res->operation_type = '更新';
            $res->operation_object = $data->name;
            $res->content = $user->name . '更新' . $data->name . '项目';
            $res->created_at = $data->created_at;
            $res->save();
        } catch (\Exception $e) {


        }
    }

    public function delete()
    {
        try {
            $id = Auth::id();
            $data = Project::where('amdin_user_id', $id)->orderby('id', 'desc')->first();
            $user = User::where('id', $id)->first();
            $res = new LogTable();
            $res->user_id = $id;
            $res->operation_type = '删除';
            $res->operation_object = $data->name;
            $res->content = $user->name . '删除了' . $data->name . '项目';
            $res->created_at = $data->created_at;
            $res->save();
        } catch (\Exception $e) {


        }
    }
}
