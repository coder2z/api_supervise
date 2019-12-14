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

//魏子超
Route::get('logs', 'Logs\LogController@logs');//

//zhengruyuan
// Route::prefix('projectadmin')->namespace('ProjectAdmin')->group(function () {
    //     Route::get('getAllUsers', 'UserController@getAllUsers');//显示全部人员    {项目管理员只能看到自己的项目！这里查询的是所有}
    //     Route::get('getUpdateUser/{id}', 'UserController@getUpdateUser');//获取要修改的人员 {获取但是使用的model方法是删除}
    //     Route::post('updateUser/{id}', 'UserController@updateUser');//修改人员  {这里只能修改类型}
    //     Route::get('deleteUser/{id}', 'UserController@deleteUser');//移除人员   {这里应该直接在项目人员表中删除就行，而不是设置项目id为0}
    //     Route::get('getUsers', 'UserController@getUsers');//获取人员(根据传入参数的不同获取不同人员)   {成员类型是什么？}
    //     Route::post('searchUser', 'UserController@searchUser');//搜索人员
    // });
//zhengruyuan
Route::prefix('projectadmin')->namespace('ProjectAdmin')->group(function(){
    Route::get('getAllUsers','UserController@getAllUsers');//显示全部人员
    Route::get('getUpdateUser/{id}','UserController@getUpdateUser');//获取要修改的人员
    Route::post('updateUser/{id}','UserController@updateUser');//修改人员
    Route::get('deleteUser/{id}','UserController@deleteUser');//移除人员
    Route::get('getUsers','UserController@getUsers');//获取所有人员(不包括负责人)
    Route::post('searchUser','UserController@searchUser');//搜索人员
});
    
    //易康
    Route::prefix('ProjectAdmin')->namespace('ProjectAdmin')->group(function () {
    Route::get('getAllProject', 'ProjectController@getAllProject'); //项目管理员获取全部项目信息
    Route::get('getProject/{id}', 'ProjectController@getProject'); //获取指定{id}项目信息
    Route::post('setProject/{id}', 'ProjectController@setProject'); //修改项目
    Route::post('addProject', 'ProjectController@addProject'); //添加项目
    Route::delete('deleteProject/{id}', 'ProjectController@deleteProject'); //删除项目
});

//人员管理
Route::namespace('Admin')->group(function () {
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
Route::prefix('ProjectAdmin')->namespace('ProjectAdmin')->group(function () {
    Route::get('membersItem', 'InviteController@getMembersItem');//查询所有用户
    Route::get('addMembers', 'InviteController@addMembers');//添加项目成员
    Route::post('queryUsers', 'InviteController@queryUsers');//查询用户
});
