<?php

namespace App\Http\Controllers\Resposer;

use App\Model\InterfaceTable;
use App\Model\ResponseTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UrlController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function url(Request $request,$project_id){
        $result = InterfaceTable::leftjoin('request_tables','interface_tables.id','request_tables.interface_id')
            ->leftjoin('project_modules','interface_tables.module_id','project_modules.id')
            ->leftjoin('projects','project_modules.project_id','projects.id')
            ->where('projects.id',$project_id)
            ->where('route_path',$request->route)
            ->where('interface_tables.state',1)->first();
        if (is_null($result)){
            return response()->fail(100, "没有该接口!");
        }
        $id = $result->id;
        $error = ResponseTable::where('interface_id',$id)->where('state',0)->first();
        $error_msg = $error->response_data;
        for ($k = 0; $k < $error->paginate_per_num; $k++){
            $data['data'][$k] = $error_msg;
        }
        $success = ResponseTable::where('interface_id',$id)->where('state',1)->first();
        $succ_msg = $success->response_data;
        if(!$request->isMethod($result->request_mode)){
            return response($data);
        }
        $params = (json_decode($result->params))->interface_request;
        $header = (json_decode($result->header));
        for ($j = 0; $j < sizeof($header); $j++){
            if (!$header[$j]->header_default_value == ($request->header())[$header[$j]->header_name][0]){
                return response($data);
            }
            if (!array_key_exists($header[$j]->header_name,$request->header())){
                return response($data);
            }
        }
        for ($i = 0; $i < sizeof($params); $i++){
            if (is_null($request->input($params[$i]->request_name))){
                return response($data);
            }
        }
        for ($k = 0; $k < $success->paginate_per_num; $k++){
            $data['data'][$k] = $succ_msg;
        }
        return response($data);
    }
}
