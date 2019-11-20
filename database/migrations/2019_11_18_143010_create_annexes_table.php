<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annexes', function (Blueprint $table) {
            $table->increments('id')->comment('附件表主键id');//附件表主键id

            $table->integer('project_id')->unsigned()->comment('外键projects表主键id,设置restrict');
            $table->foreign('project_id')//外键projects表主键id,设置restrict
                    ->references('id')->on('projects');

            $table->string('path')->comment('附件路径');//附件路径
            $table->char('type',2)->default('-1')->comment('项目类型 0为sql。1为原型图。2为需求文档 -1 未定');//项目类型 0为sql。1为原型图。2为需求文档 -1 未定
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
        Schema::dropIfExists('annexes');
    }
}
