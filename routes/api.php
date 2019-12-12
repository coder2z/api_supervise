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
});

Route::get('/ProjectAdmin/getWord','ProjectAdmin\WordController@getWord');

Route::any('/ProjectAdmin/test','ProjectAdmin\WordController@Test');


//zhengruyuan
Route::prefix('projectadmin')->namespace('ProjectAdmin')->group(function () {
    Route::get('getAllUsers', 'UserController@getAllUsers');//显示全部人员
    Route::get('getUpdateUser/{id}', 'UserController@getUpdateUser');//获取要修改的人员
    Route::post('updateUser/{id}', 'UserController@updateUser');//修改人员
    Route::get('deleteUser/{id}', 'UserController@deleteUser');//移除人员
    Route::get('getUsers', 'UserController@getUsers');//获取人员(根据传入参数的不同获取不同人员)
    Route::post('searchUser', 'UserController@searchUser');//搜索人员
});

//yikang
Route::prefix('ProjectAdmin')->namespace('ProjectAdmin')->group(function () {
    Route::get('getAllProject', 'ProjectController@getAllProject');
    Route::get('getProject/{id}', 'ProjectController@getProject');
    Route::post('setProject/{id}', 'ProjectController@setProject');
    Route::post('addProject', 'ProjectController@addProject');
    Route::delete('deleteProject/{id}', 'ProjectController@deleteProject');
});

//qiu
Route::namespace('Admin')->group(function (){
    Route::get('getUser','AdminController@getUser')->middleware('manage');//获取某个状态下的所有用户
    Route::get('DeleteUser','AdminController@DeleteUser')->middleware('manage');//删除用户
    Route::post('SearchUser','AdminController@SearchUser')->middleware('manage');//搜索用户
    Route::get('ShowUserInfo','AdminController@ShowUserInfo')->middleware('manage');//展示用户信息
    Route::post('UpdateUserInfo','AdminController@UpdateUserInfo')->middleware('manage');//修改用户信息
    Route::post('AddUser','AdminController@AddUser')->middleware('manage');//新增用户信息
});
