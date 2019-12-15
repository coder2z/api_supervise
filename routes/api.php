<?php /** @noinspection PhpParamsInspection */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


use Illuminate\Support\Facades\Route;

Route::prefix('oAuth')->namespace('OAuth')->group(function (){
    Route::post('login','AuthController@login');//登陆
    Route::post('info','AuthController@info');//获取用户信息
    Route::post('registered','AuthController@registered');//用户注册
    Route::post('logout','AuthController@logout');//退出登陆
    Route::post('refresh','AuthController@refresh');//刷新token
});
/**
 * 文艺
 *     获取所有项目
 */
Route::prefix('QueryAllProject')->namespace('QueryAllProject')->group(function(){
    Route::get('getAllProject','ProjectSearchController@getAllProject');
});


/**
 * 聂鹏郦
 *      1. 项目配置文件信息
 *      2. 提供下载
 */
Route::prefix('frontend')->namespace('FrontEnd')->group(function(){
    Route::get('getConfigFile','ProjectSettingController@getConfigFile');//获取所有接口
    Route::get('downloadConfigFile','ProjectSettingController@downloadConfigFile');//获取所有接口
});

Route::prefix('backend')->namespace('BackEnd')->group(function(){
    Route::get('getConfigFile','ProjectSettingController@getConfigFile');//获取所有接口
    Route::get('downloadConfigFile','ProjectSettingController@downloadConfigFile');//获取所有接口
});


/**
 * 蒋武君
 *      1. 上传修改更新文件及GitHub地址
 *      2. 任务管理的人员分配CRUD
 */
Route::prefix('')->namespace('BackendManager')->group(function(){
    Route::get("getConfigurationFileSetting","SettingFileController@getConfigurationFileSetting");
    Route::post("updateConfigurationFileSetting","SettingFileController@updateConfigurationFileSetting");
    Route::post("addConfigurationFileSetting","SettingFileController@addConfigurationFileSetting");
    Route::post("uploadConfigurationFile","SettingFileController@uploadConfigurationFile");

    Route::get("getAllAssignments","TaskManagerController@getAllAssignments");
    Route::post("addAssignment","TaskManagerController@addAssignment");
    Route::post("updateAssignment","TaskManagerController@updateAssignment");
    Route::get("deleteAssignment","TaskManagerController@deleteAssignment");
});

 /**
  * 仇政永
  *     1. 审核接口
  *     2. 接口查询和筛选
  */
Route::prefix('check')->namespace('BackendManager')->group(function(){
    Route::get('get_API_all','AccessStateController@getAPIAll');//获取所有接口
    Route::get('get_API_Info/{interface_id}','AccessStateController@getAPIInfoByID');//根据ID获取指定接口的所有信息
    Route::get('check/{interface_id}','AccessStateController@checkAPI');//修改指定接口审核状态
});
 
Route::prefix('backend')->namespace('BackEnd')->group(function(){
    Route::get('interface_distribute','InterfaceAllotController@getAllInterface');//获取所有接口
    Route::get('interface_screen','InterfaceAllotController@screenInterface');//获取所有接口
});

Route::prefix('frontend')->namespace('FrontEnd')->group(function(){
    Route::get('interface_distribute','InterfaceAllotController@getAllInterface');//获取所有接口
    Route::get('interface_screen','InterfaceAllotController@screenInterface');//获取所有接口
});

/**
 * 李承坤
 *     1. 前端人员对接口进行查询、搜索、添加是否交互
 *     2. 后端人员对接口进行增删查改，批量删除
 */
Route::prefix('frontend')->namespace('FrontEnd')->group(function () {
    //通过user_id获取接口列表
    Route::get('interface','InterfaceManagerController@index');
    //搜索接口
    Route::get('interface/search','InterfaceManagerController@searchInterface');
    //修改接口交互状态
    Route::put('interface/{interface_id}','InterfaceManagerController@setInteractiveState');
});
Route::prefix('backend')->namespace('BackEnd')->group(function () {
    //后端新增接口
    Route::post('interface','InterfaceManagerController@store');
    //后端获取接口
    Route::get('interface/{interface_id}','InterfaceManagerController@show');
    //后端修改接口
    Route::put('interface/{interface_id}','InterfaceManagerController@save');
    //后端删除接口
    Route::delete('interface/{interface_id}','InterfaceManagerController@destroy');
    //后端批量删除接口
    Route::delete('interfaces','InterfaceManagerController@destroySelect');
    //后端查看模块名
    Route::get('module_names/{projectId}','InterfaceManagerController@showModuleName');
    //后端查看错误码
    Route::get('error_code/{projectId}','InterfaceManagerController@showErrorCode');
});

/**
 * 欧阳生林
 *       1. 模块设置.
 *       2.错误码设置
 */
Route::group(['prefix'=>'module','namespace'=>'BackendManager'],function (){
    Route::get('selectModule','ModuleSettingController@selectModule');
    Route::post('addModule','ModuleSettingController@addModule');
    Route::put('editModule','ModuleSettingController@editModule');
    Route::delete('deModule','ModuleSettingController@deModule');
});

Route::group(['prefix'=>'errCode','namespace'=>'BackendManager'],function (){
    Route::get('selectErrorCode','ErrorCodeSettingController@selectErrorCode');
    Route::post('addErrorCode','ErrorCodeSettingController@addErrorCode');
    Route::put('editErrorCode','ErrorCodeSettingController@editErrorCode');
    Route::delete('deErrorCode','ErrorCodeSettingController@deErrorCode');
});
 