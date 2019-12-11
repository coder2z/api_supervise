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

Route::prefix('oAuth')->namespace('OAuth')->middleware('throttle')->group(function (){
    Route::post('login','AuthController@login');//登陆
    Route::post('info','AuthController@info');//获取用户信息
    Route::post('registered','AuthController@registered');//用户注册
    Route::post('logout','AuthController@logout');//退出登陆
    Route::post('refresh','AuthController@refresh');//刷新token
});


Route::get('/ProjectAdmin/getWord','ProjectAdmin\WordController@getWord');


Route::any('/ProjectAdmin/test','ProjectAdmin\WordController@Test');