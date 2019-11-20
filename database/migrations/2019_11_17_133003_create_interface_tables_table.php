<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterfaceTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interface_tables', function (Blueprint $table) {
            $table->increments('id')->comment('接口表主键id');//接口表主键id
            $table->text('interface_discribe')->comment('接口描述');//接口描述
            $table->string('interface_name')->comment('接口名');//接口名
            $table->string('function_name')->comment('类方法名');//类方法名

            $table->integer('module_id')->unsigned()->comment('外键project_modules表主键id,设置restrict');
            $table->foreign('module_id')//外键project_modules表主键id,设置restrict
                    ->references('id')->on('project_modules');

            $table->string('route_path')->comment('路由');//路由
            $table->char('state',1)->default('0')->comment('状态 0审核 1通过 2不通过');//状态 0审核 1通过 2不通过
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
        Schema::dropIfExists('interface_tables');
    }
}
