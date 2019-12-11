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

//yikang
Route::prefix('ProjectAdmin')->namespace('ProjectAdmin')->group(function () {
    Route::get('getAllProject', 'ProjectController@getAllProject');
    Route::get('getProject/{id}', 'ProjectController@getProject');
    Route::post('setProject/{id}', 'ProjectController@setProject');
    Route::post('addProject', 'ProjectController@addProject');
    Route::delete('deleteProject/{id}', 'ProjectController@deleteProject');
});

//人员管理
Route::namespace('Admin')->group(function (){
    Route::get('getUser','AdminController@getUser');//获取某个状态下的所有用户
    Route::get('DeleteUser','AdminController@DeleteUser');//删除用户
    Route::post('SearchUser','AdminController@SearchUser');//搜索用户
    Route::get('ShowUserInfo','AdminController@ShowUserInfo');//展示用户信息
    Route::post('UpdateUserInfo','AdminController@UpdateUserInfo');//修改用户信息
    Route::post('AddUser','AdminController@AddUser');//新增用户信息
})->middleware('manage');
