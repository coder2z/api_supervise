<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedBacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_backs', function (Blueprint $table) {
            $table->increments('id')->comment('反馈表主键id');//反馈表主键id
            $table->integer('from_uesr_id')->unsigned()->comment('外键user表主键id,设置restrict');
            $table->foreign('from_uesr_id')//外键user表主键id,设置restrict
            ->references('id')->on('users');

            $table->integer('to_user_id')->comment('外键user表主键id,设置restrict')->nullable();
            $table->integer('project_id')->comment('projects表主键id')->nullable();
            $table->integer('interface_id')->comment('interface表主键id')->nullable();

            $table->char('broadcast', 2)->default('-1')->comment('职位代码 0前端 1后端 -1未规定');//职位代码 0前端 1后端 -1未规定
            $table->text('content')->comment('反馈内容');//反馈内容

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
        Schema::dropIfExists('feed_backs');
    }
}
