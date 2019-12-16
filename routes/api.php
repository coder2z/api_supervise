<?php

/** @noinspection PhpParamsInspection */

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

Route::prefix('oAuth')->namespace('OAuth')->group(function () {
    Route::post('login', 'AuthController@login'); //登陆
    Route::post('info', 'AuthController@info'); //获取用户信息
    Route::post('registered', 'AuthController@registered'); //用户注册
    Route::post('logout', 'AuthController@logout'); //退出登陆
    Route::post('refresh', 'AuthController@refresh'); //刷新token
    Route::post('changePassword', 'AuthController@changePassword');//修改密码
});
Route::middleware('auth.check')->group(function () {
//魏子超
    Route::get('logs', 'Logs\LogController@logs');
//zhengruyuan
    Route::prefix('projectadmin')->namespace('ProjectAdmin')->middleware('auth.prject.admin')->group(function () {
        Route::get('getAllUsers', 'UserController@getAllUsers');//显示全部人员    {项目管理员只能看到自己的项目！这里查询的是所有}
        Route::get('getUpdateUser/{id}', 'UserController@getUpdateUser')
            ->where('id', '[0-9]+');//获取要修改的人员 {获取但是使用的model方法是删除}
        Route::post('updateUser/{id}', 'UserController@updateUser')
            ->where('id', '[0-9]+');//修改人员  {这里只能修改类型}
        Route::get('deleteUser/{id}', 'UserController@deleteUser')
            ->where('id', '[0-9]+');//移除人员   {这里应该直接在项目人员表中删除就行，而不是设置项目id为0}
        Route::get('getUsers', 'UserController@getUsers');//获取人员(根据传入参数的不同获取不同人员)   {成员类型是什么？}
        Route::post('searchUser', 'UserController@searchUser');//搜索人员
    });
//易康
    Route::prefix('ProjectAdmin')->namespace('ProjectAdmin')->middleware('auth.prject.admin')->group(function () {
        Route::get('getAllProject', 'ProjectController@getAllProject'); //项目管理员获取全部项目信息
        Route::get('getProject/{id}', 'ProjectController@getProject')
            ->where('id', '[0-9]+'); //获取指定{id}项目信息
        Route::post('setProject/{id}', 'ProjectController@setProject')
            ->where('id', '[0-9]+'); //修改项目
        Route::post('addProject', 'ProjectController@addProject'); //添加项目
        Route::delete('deleteProject/{id}', 'ProjectController@deleteProject')
            ->where('id', '[0-9]+'); //删除项目
    });

//人员管理
    Route::namespace('Admin')->middleware('manage')->group(function () {
        Route::get('getUser', 'AdminController@getUser');//获取某个状态下的所有用户 {getInfo 模型里面没有}
        Route::get('DeleteUser', 'AdminController@DeleteUser');//删除用户 {模型调用 DeleteUser   被用过了}
        Route::post('SearchUser', 'AdminController@SearchUser');//搜索用户  {Search 模型里面没有}
        Route::get('ShowUserInfo', 'AdminController@ShowUserInfo');//展示用户信息 {ShowUserInfo   模型里面没有}
        Route::post('UpdateUserInfo', 'AdminController@UpdateUserInfo');//修改用户信息    {UpdateUserInfo 模型里面没有}
        Route::post('AddUser', 'AdminController@AddUser');//新增用户信息{AddUser  模型里面没有}
    });

//倪煜
    Route::get("/MyMessage", "Message\FrontEndMsgController@MyMessage");//获取反馈信息列表
    Route::get("/SendMail_All_Back", "Message\FrontEndMsgController@SendMail_All_Back");//前端增加所在项目反馈信息并发送邮件（项目全部后端）

//刘志伟
    Route::prefix('Message')->namespace('Message')->group(function () {
        Route::get('showMessage', 'BackEndMsgController@showMessage');//查看所有消息
        Route::get('fromMessage', 'BackEndMsgController@fromMessage');//查看我的反馈
        Route::get('toMessage', 'BackEndMsgController@toMessage');//查看发给我的消息
    });

//zhangmaolin
    Route::prefix('Message')->namespace('Message')->group(function () {
        Route::post('FrontEndInterfaceController', 'FrontEndInterfaceController@FrontEndInterfaceController');//前端将信息反馈给后端  {邮件}
        Route::get('viewFeedback ', 'viewFeedback@viewFeedback');//查看反馈信息   {一个人会有很多的反馈消息，这里查询有问题}
    });

//吕永杰
    Route::prefix('ProjectAdmin')->namespace('ProjectAdmin')->middleware('auth.prject.admin')->group(function () {
        Route::get('membersItem', 'InviteController@getMembersItem');//查询所有用户
        Route::get('addMembers', 'InviteController@addMembers');//添加项目成员
        Route::post('queryUsers', 'InviteController@queryUsers');//查询用户
    });
    /**
     * 文艺
     *     获取所有项目
     */
    Route::prefix('QueryAllProject')->namespace('QueryAllProject')->group(function () {
        Route::get('getAllProject', 'ProjectSearchController@getAllProject');
    });

    /**
     * 聂鹏郦
     *      1. 项目配置文件信息
     *      2. 提供下载
     */
    Route::prefix('frontend')->namespace('FrontEnd')->group(function () {
        Route::get('getConfigFile', 'ProjectSettingController@getConfigFile');//获取所有接口   {表单验证，try{}catch{}，日志}
        Route::get('downloadConfigFile', 'ProjectSettingController@downloadConfigFile');//获取所有接口    {表单验证，try{}catch{}，日志}
    });

    /**
     * 蒋武君
     *      1. 上传修改更新文件及GitHub地址
     *      2. 任务管理的人员分配CRUD
     */
    Route::prefix('')->namespace('BackendManager')->group(function () {
        Route::get("getConfigurationFileSetting", "SettingFileController@getConfigurationFileSetting");// {表单验证，try{}catch{},日志}
        Route::post("updateConfigurationFileSetting", "SettingFileController@updateConfigurationFileSetting");//
        Route::post("addConfigurationFileSetting", "SettingFileController@addConfigurationFileSetting");
        Route::post("uploadConfigurationFile", "SettingFileController@uploadConfigurationFile");//{文件验证}
        Route::get("getAllAssignments", "TaskManagerController@getAllAssignments");//{try{}catch{}}
        Route::post("addAssignment", "TaskManagerController@addAssignment");
        Route::post("updateAssignment", "TaskManagerController@updateAssignment");
        Route::get("deleteAssignment", "TaskManagerController@deleteAssignment");
    });

    /**
     * 仇政永
     *     1. 审核接口
     *     2. 接口查询和筛选
     */
    Route::prefix('check')->namespace('BackendManager')->group(function () {
        Route::get('get_API_all', 'AccessStateController@getAPIAll');//获取所有接口
        Route::get('get_API_Info/{interface_id}', 'AccessStateController@getAPIInfoByID')
            ->where('interface_id', '[0-9]+');//根据ID获取指定接口的所有信息
        Route::put('check/{interface_id}', 'AccessStateController@checkAPI')
            ->where('interface_id', '[0-9]+');//修改指定接口审核状态
    });

    Route::prefix('backend')->namespace('BackEnd')->group(function () {
        Route::get('interface_distribute', 'InterfaceAllotController@getAllInterface');//获取所有接口
        Route::get('interface_screen', 'InterfaceAllotController@screenInterface');//获取所有接口
    });

    Route::prefix('frontend')->namespace('FrontEnd')->group(function () {
        Route::get('interface_distribute', 'InterfaceAllotController@getAllInterface');//获取所有接口
        Route::get('interface_screen', 'InterfaceAllotController@screenInterface');//获取所有接口
    });

    /**
     * 李承坤
     *     1. 前端人员对接口进行查询、搜索、添加是否交互
     *     2. 后端人员对接口进行增删查改，批量删除
     */
    Route::prefix('frontend')->namespace('FrontEnd')->middleware('auth.front')->group(function () {
        //通过user_id获取接口列表
        Route::get('interface', 'InterfaceManagerController@index');//{try{}catch{}}
        //搜索接口
        Route::get('interface/search', 'InterfaceManagerController@searchInterface');
        //修改接口交互状态
        Route::put('interface/{interface_id}', 'InterfaceManagerController@setInteractiveState')
            ->where('interface_id', '[0-9]+');
    });
    Route::prefix('backend')->namespace('BackEnd')->middleware('auth.queen')->group(function () {
        //后端新增接口
        Route::post('interface', 'InterfaceManagerController@store');
        //后端获取接口
        Route::get('interface/{interface_id}', 'InterfaceManagerController@show')
            ->where('interface_id', '[0-9]+');
        //后端修改接口
        Route::put('interface/{interface_id}', 'InterfaceManagerController@save')
            ->where('interface_id', '[0-9]+');
        //后端删除接口
        Route::delete('interface/{interface_id}', 'InterfaceManagerController@destroy')
            ->where('interface_id', '[0-9]+');
        //后端批量删除接口
        Route::delete('interfaces', 'InterfaceManagerController@destroySelect');
        //后端查看模块名
        Route::get('module_names/{projectId}', 'InterfaceManagerController@showModuleName')
            ->where('projectId', '[0-9]+');
        //后端查看错误码
        Route::get('error_code/{projectId}', 'InterfaceManagerController@showErrorCode')
            ->where('projectId', '[0-9]+');
    });

    /**
     * 欧阳生林 ～后端管理员
     *       1. 模块设置.
     *       2.错误码设置
     */
    Route::group(['prefix' => 'module', 'namespace' => 'BackendManager', 'middleware' => 'auth.queen.admin'], function () {
        Route::get('selectModule', 'ModuleSettingController@selectModule');
        Route::post('addModule', 'ModuleSettingController@addModule');
        Route::put('editModule/{m_id}', 'ModuleSettingController@editModule')
            ->where('m_id', '[0-9]+');
        Route::delete('deModule/{m_id}', 'ModuleSettingController@deModule')
            ->where('m_id', '[0-9]+');
    });
    Route::group(['prefix' => 'errCode', 'namespace' => 'BackendManager', 'middleware' => 'auth.queen.admin'], function () {
        Route::get('selectErrorCode', 'ErrorCodeSettingController@selectErrorCode');
        Route::post('addErrorCode', 'ErrorCodeSettingController@addErrorCode');
        Route::put('editErrorCode/{m_id}', 'ErrorCodeSettingController@editErrorCode')
            ->where('m_id', '[0-9]+');
        Route::delete('deErrorCode/{m_id}', 'ErrorCodeSettingController@deErrorCode')
            ->where('m_id', '[0-9]+');
    });
    Route::get('/ProjectAdmin/getWord','ProjectAdmin\WordController@getWord')->middleware('auth.prject.admin');
    Route::any('url', 'Resposer/UrlController@url'); //模拟响应
});
 