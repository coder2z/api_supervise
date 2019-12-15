<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    \App\Utils\Logs::logError('用户插入失败',["wsadaww"]);
    // \App\Utils\Logs::logWarning('用户插入失败',["wsadaww"]);
    // \App\Utils\Logs::logInfo('用户插入失败',["wsadaww"]);


});

