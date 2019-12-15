<?php

namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Utils\Logs;

use App\Model\InterfaceTable;
use App\Model\Error;
use App\Model\ErrorRelation;
use App\Model\RequestTable;
use App\Model\ResponseTable;
use App\Model\ProjectModule;

class InterfaceManagerController extends Controller
{
    //后端新建接口
    public function store(Request $request)
    {
        $userId = auth()->id();

        $data = $request->getContent();
        $data = json_decode($data);

        //string 接口名 interface_tables.interface_name
        $interface_name = $data->interface_name;
        //string 接口名 interface_tables.interface_name
        $interface_function_name = $data->interface_function_name;
        //string 请求方法 request_tables.request_mode
        $interfaec_method = $data->interfaec_method;
        //string 请求url interface_tables.route_path
        $interface_url = $data->interface_url;
        //int 模块id interface_tables.module_id
        $interface_module_id = $data->interface_module_id;
        //string 返回类型 response_tables.response_data_type
        $interface_response_type = $data->interface_response_type;
        //array int 错误码 errors.id error_relations
        $interface_response_error_code_ids = $data->interface_response_error_code_id;
        //string 接口说明 interface_tables.interface_discribe
        $interface_intro = $data->interface_intro;
        //array->obj 请求头的内容 request_tables.header
        $interface_header = $data->interface_header;
        //string 请求参数类型 request_tables.request_mode
        $interface_request_type = $data->interface_request_type;
        //array->obj 请求参数内容 request_tables.params
        $interface_request = $data->interface_request;
        //string 成功返回示例 response_tables.response_data
        $interface_response_success_example = $data->interface_response_success_example;
        //string 错误返回示例 response_tables.response_data
        $interface_response_fail_example = $data->interface_response_fail_example;

        DB::beginTransaction();
        try {
            $interfaces = new InterfaceTable;
            $requestTable = new RequestTable;
            $responseTableFail = new ResponseTable;
            $responseTableSuccess = new ResponseTable;

            $interfaces->interface_name = $interface_name;
            $interfaces->route_path = $interface_url;
            $interfaces->interface_discribe = $interface_intro;
            $interfaces->module_id = $interface_module_id;
            $interfaces->function_name = $interface_function_name;

            $requestTable->request_mode = $interfaec_method;
            $requestTable->params = json_encode(['interface_request_type' => $interface_request_type,
                'interface_request' => $interface_request]);
            $requestTable->header = json_encode($interface_header);

            $responseTableFail->response_data_type = $interface_response_type;
            $responseTableSuccess->response_data_type = $interface_response_type;
            $responseTableFail->response_data = $interface_response_fail_example;
            $responseTableFail->state = 0;
            $responseTableSuccess->response_data = $interface_response_success_example;
            $responseTableSuccess->state = 1;

            $interfaces->save();
            foreach ($interface_response_error_code_ids as $key => $value) {
                $errRelation = new ErrorRelation;
                $errRelation->error_id = $value;
                $errRelation->interface_id = $interfaces->id;
                $errRelation->save();
            }
            $requestTable->interface_id = $interfaces->id;
            $responseTableFail->interface_id = $interfaces->id;
            $responseTableSuccess->interface_id = $interfaces->id;

            $requestTable->save();
            $responseTableFail->save();
            $responseTableSuccess->save();
            DB::commit();
            Logs::logInfo("用id为{$userId}添加接口成功.接口id为{$interfaces->id}.");
            return response()->success(200, '添加成功', null);
        } catch (\Exception $e) {
            DB::rollBack();
            Logs::logError("用id为{$userId}添加接口错误，数据库详细报错:" . $e->getMessage());
            return response()->fail(100, '添加失败', null);
        }
    }

    //后端显示一个接口
    public function show($interface_id)
    {
        $userId = auth()->id();
        DB::beginTransaction();
        try {
            $interfaces = InterfaceTable::find($interface_id);
            $requestTable = RequestTable::where('interface_id', $interface_id)->first();
            $responseTableFail = ResponseTable::where(['interface_id' => $interface_id, 'state' => 0])->first();
            $responseTableSuccess = ResponseTable::where(['interface_id' => $interface_id, 'state' => 1])->first();

            $returnRes['interface_id'] = $interfaces->id;
            $returnRes['interface_name'] = $interfaces->interface_name;
            $returnRes['interfaec_method'] = $requestTable->request_mode;
            $returnRes['interface_url'] = $interfaces->route_path;
            $returnRes['interface_function_name'] = $interfaces->function_name;
            $returnRes['interface_response_type'] = $responseTableSuccess->response_data_type;
            $returnRes['interface_intro'] = $interfaces->interface_discribe;
            $returnRes['interface_header'] = json_decode($requestTable->header);
            $returnRes['interface_response_success_example'] = $responseTableSuccess->response_data;
            $returnRes['interface_response_fail_example'] = $responseTableFail->response_data;
            $returnRes['interface_request_type'] = json_decode($requestTable->params)->interface_request_type;
            $returnRes['interface_request'] = json_decode($requestTable->params)->interface_request;
            $returnRes['interface_module_id'] = $interfaces->module_id;

            $errorIdsObj = ErrorRelation::where('interface_id', $interfaces->id)->select('error_id')->get();
            foreach ($errorIdsObj as $key => $value) {
                $errorIdsArray[] = $value->error_id;
            }
            $returnRes['interface_response_error_code_ids'] = $errorIdsArray;
            DB::commit();
            Logs::logInfo("用id为{$userId}查询接口成功.接口id为{$interface_id}.");
            return response()->success(200, '查询成功', $returnRes);
        } catch (\Exception $e) {
            DB::rollBack();
            Logs::logError("用id为{$userId}查询接口错误，数据库详细报错:" . $e->getMessage());
            return response()->fail(100, '查询失败', null);
        }
    }

    //后端修改一个接口
    public function save(Request $request, $interface_id)
    {
        $userId = auth()->id();

        $data = $request->getContent();
        $data = json_decode($data);

        //string 接口名 interface_tables.interface_name
        $interface_name = $data->interface_name;
        //string 接口名 interface_tables.interface_name
        $interface_function_name = $data->interface_function_name;
        //string 请求方法 request_tables.request_mode
        $interfaec_method = $data->interfaec_method;
        //string 请求url interface_tables.route_path
        $interface_url = $data->interface_url;
        //int 模块id interface_tables.module_id
        $interface_module_id = $data->interface_module_id;
        //string 返回类型 response_tables.response_data_type
        $interface_response_type = $data->interface_response_type;
        //array int 错误码 errors.id error_relations
        $interface_response_error_code_ids = $data->interface_response_error_code_id;
        //string 接口说明 interface_tables.interface_discribe
        $interface_intro = $data->interface_intro;
        //array->obj 请求头的内容 request_tables.header
        $interface_header = $data->interface_header;
        //string 请求参数类型 request_tables.request_mode
        $interface_request_type = $data->interface_request_type;
        //array->obj 请求参数内容 request_tables.params
        $interface_request = $data->interface_request;
        //string 成功返回示例 response_tables.response_data
        $interface_response_success_example = $data->interface_response_success_example;
        //string 错误返回示例 response_tables.response_data
        $interface_response_fail_example = $data->interface_response_fail_example;

        DB::beginTransaction();
        try {
            $interfaces = InterfaceTable::find($interface_id);
            $requestTable = RequestTable::where('interface_id', $interface_id)->first();
            $responseTableFail = ResponseTable::where(['interface_id' => $interface_id, 'state' => 0])->first();
            $responseTableSuccess = ResponseTable::where(['interface_id' => $interface_id, 'state' => 1])->first();

            $interfaces->interface_name = $interface_name;
            $interfaces->route_path = $interface_url;
            $interfaces->interface_discribe = $interface_intro;
            $interfaces->module_id = $interface_module_id;
            $interfaces->function_name = $interface_function_name;

            $requestTable->request_mode = $interfaec_method;
            $requestTable->params = json_encode(['interface_request_type' => $interface_request_type,
                'interface_request' => $interface_request]);
            $requestTable->header = json_encode($interface_header);

            $responseTableFail->response_data_type = $interface_response_type;
            $responseTableSuccess->response_data_type = $interface_response_type;
            $responseTableFail->response_data = $interface_response_fail_example;
            $responseTableSuccess->response_data = $interface_response_success_example;

            $interfaces->save();
            ErrorRelation::where('interface_id', $interface_id)->delete();
            foreach ($interface_response_error_code_ids as $key => $value) {
                $errRelation = new ErrorRelation;
                $errRelation->error_id = $value;
                $errRelation->interface_id = $interfaces->id;
                $errRelation->save();
            }

            $requestTable->save();
            $responseTableFail->save();
            $responseTableSuccess->save();
            DB::commit();
            Logs::logInfo("用id为{$userId}修改接口成功.修改接口id为{$interfaces->id}.");
            return response()->success(200, '修改成功', null);
        } catch (\Exception $e) {
            DB::rollBack();
            Logs::logError("用id为{$userId}修改接口错误，数据库详细报错:" . $e->getMessage());
            return response()->fail(100, '修改失败', null);
        }
    }

    //删除一个接口
    public function destroy($interface_id)
    {
        $userId = auth()->id();

        DB::beginTransaction();
        try {
            $interfaces = InterfaceTable::find($interface_id);
            $requestTable = RequestTable::where('interface_id', $interface_id)->first();
            $responseTableFail = ResponseTable::where(['interface_id' => $interface_id, 'state' => 0])->first();
            $responseTableSuccess = ResponseTable::where(['interface_id' => $interface_id, 'state' => 1])->first();
            if (empty($interfaces)) {
                Logs::logError("用id为{$userId}删除接口错误，未找到接口id:" . $interface_id);
                return response()->fail(100, '删除失败', null);
            }
            ErrorRelation::where('interface_id', $interface_id)->delete();
            $requestTable->delete();
            $responseTableFail->delete();
            $responseTableSuccess->delete();
            $interfaces->delete();
            DB::commit();
            Logs::logInfo("用id为{$userId}删除接口成功.删除接口id为{$interfaces->id}.");
            return response()->success(200, '删除成功', null);
        } catch (\Exception $e) {
            DB::rollBack();
            Logs::logError("用id为{$userId}删除接口错误，数据库详细报错:" . $e->getMessage());
            return response()->fail(100, '删除失败', null);
        }
    }

    //删除一些接口
    public function destroySelect(Request $request)
    {
        $userId = auth()->id();

        $data = $request->getContent();
        $data = json_decode($data);
        DB::beginTransaction();
        try {
            DB::table('response_tables')->whereIn('interface_id', $data)->delete();
            DB::table('request_tables')->whereIn('interface_id', $data)->delete();
            DB::table('error_relations')->whereIn('interface_id', $data)->delete();
            DB::table('interface_tables')->whereIn('id', $data)->delete();
            DB::commit();
            $data = json_encode($data);
            Logs::logInfo("用id为{$userId}删除接口成功.删除接口id为{$data}.");
            return response()->success(200, '删除成功', null);
        } catch (\Exception $e) {
            DB::rollBack();
            Logs::logError("用id为{$userId}删除接口错误，数据库详细报错:" . $e->getMessage());
            return response()->fail(100, '删除失败', null);
        }
    }

    //显示模块名
    public function showModuleName($projectId)
    {
        $userId = auth()->id();
        $res = ProjectModule::select(['id', 'modules_name'])->where('project_id', $projectId)->get();
        $res = json_decode(json_encode($res));
        if (empty($res)) {
            Logs::logWarning("用id为{$userId}查询模块名失败，未找到数据.查询模块名项目id为{$projectId}.");
            return response()->fail(100, '显示模块名失败，未找到对应工程模块名');
        }
        Logs::logInfo("用id为{$userId}查询模块名成功.查询模块名项目id为{$projectId}.");
        return response()->success(200, '显示模块名成功.', $res);
    }

    //显示错误码
    public function showErrorCode($projectId)
    {
        $userId = auth()->id();

        $res = Error::select(['id', 'error_code', 'error_info', 'http_code'])->where('project_id', $projectId)->get();
        $res = json_decode(json_encode($res));
        if (empty($res)) {
            Logs::logWarning("用id为{$userId}查询错误码失败，未找到数据.查询错误码项目id为{$projectId}.");
            return response()->fail(100, '显示错误码失败，未找到对应工程错误码');
        }
        Logs::logInfo("用id为{$userId}查询错误码成功.查询错误码项目id为{$projectId}.");
        return response()->success(200, '显示错误码成功.', $res);
    }
}
