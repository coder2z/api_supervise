<?php

namespace App\Http\Controllers\Logs;

use App\Model\LogTable;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public function logs()
    {
        $data = LogTable::getLogs();
        return $data = !null ?
            response()->success(200, '成功', $data) :
            response()->fail(100, '失败', $data);
    }
}
