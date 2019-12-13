<?php

namespace App\Http\Controllers\Logs;

use App\Model\InterfaceTable;
use App\Model\Project;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test(Request $request){
        $data = new Project();
        $data-> name = $request->name;
        $data-> discribe = $request->discrible;
        $data-> amdin_user_id = $request->amdin_user_id;
        $data-> pre_url= $request->pre_url;
        $data->save();
    }
}
