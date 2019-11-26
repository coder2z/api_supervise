<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id')->comment('任务分配表主键id');//任务分配表主键id

            $table->integer('module_id')->unsigned()->comment('外键project_modules表主键id,设置restrict');
            $table->foreign('module_id')//外键project_modules表主键id,设置restrict
                ->references('id')->on('project_modules');

            $table->integer('user_id')->unsigned()->comment('外键user表主键id,设置restrict');
            $table->foreign('user_id')//外键user表主键id,设置restrict
                ->references('id')->on('users');

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
        Schema::dropIfExists('assignments');
    }
}
