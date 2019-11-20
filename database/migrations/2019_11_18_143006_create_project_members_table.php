<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_members', function (Blueprint $table) {
            $table->increments('id')->comment('项目成员主键id');//项目成员主键id
            $table->integer('project_id')->unsigned()->comment('外键projects表主键id,设置restrict');
            $table->foreign('project_id')//外键projects表主键id,设置restrict
                    ->references('id')->on('projects');

            $table->integer('user_id')->unsigned()->comment('外键user表主键id,设置restrict');
            $table->foreign('user_id')//外键user表主键id,设置restrict
                    ->references('id')->on('users');

            $table->char('type',1)->default('0')->comment('0.后端普通成员，1为前端普通成员，2为后端管理员');//0.后端普通成员，1为前端普通成员，2为后端管理员
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
        Schema::dropIfExists('project_members');
    }
}
