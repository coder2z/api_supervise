<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('用户表主键id');//用户表主键id
            $table->string('email')->comment('用户邮件');//用户邮件
            $table->string('name')->comment('用户名');//用户名
            $table->string('password')->comment('用户密码');//用户密码
            $table->char('state',1)->default('0')->comment('状态0不能用 1正常');//状态0不能用 1正常
            $table->char('phone_number',11)->comment('电话号码');//电话号码
            $table->char('access_code',2)->default('0')->comment('项目权限 0普通 1超级管理员 -1项目管理员');//项目权限 0普通 1超级管理员 -1项目管理员
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
