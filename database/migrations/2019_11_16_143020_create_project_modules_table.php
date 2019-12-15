<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_modules', function (Blueprint $table) {
            $table->increments('id')->comment('项目主键id');//项目主键id
            $table->string('modules_name')->comment('模块名');//类名
            $table->string('class_name')->comment('类名');//类名
            $table->string('full_class_name')->comment('全类名');//全类名
            $table->string('utility')->comment('作用');//作用
            $table->integer('project_id')->unsigned()->comment('外键projects表主键id,设置restrict');
            $table->foreign('project_id')//外键projects表主键id,设置restrict
            ->references('id')->on('projects');
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
        Schema::dropIfExists('project_modules');
    }
}
