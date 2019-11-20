<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id')->comment('项目表主键id');//项目表主键id
            $table->string('name')->comment('项目名');//项目名
            $table->text('discribe')->comment('项目描述');//项目描述
            $table->integer('amdin_user_id')->unsigned()->comment('外键user表主键id,设置restrict');
            $table->foreign('amdin_user_id')//外键user表主键id,设置restrict
                    ->references('id')->on('users');
                    // ->ondelete('cascade');
            $table->string('pre_url')->comment('项目url前缀');//项目url前缀
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
        Schema::dropIfExists('projects');
    }
}
