<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponseTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('response_tables', function (Blueprint $table) {
            $table->increments('id')->comment('响应表主键id');//响应表主键id
            $table->integer('interface_id')->unsigned()->comment('外键interface_tables表主键id,设置restrict');
            $table->foreign('interface_id')//外键interface_tables表主键id,设置restrict
                    ->references('id')->on('interface_tables');
            
            $table->text('response_data')->comment('响应数据');//响应数据
            $table->string('response_data_type')->comment('响应类型');//响应类型
            $table->char('state',1)->default('1')->comment('状态 1成功，0为失败');//状态 1成功，0为失败
            $table->char('is_paginate',1)->default('0')->comment('0为不分页，1为分页');//0为不分页，1为分页
            $table->integer('paginate_per_num')->default('0')->comment('分页输出数量');//分页输出数量
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
        Schema::dropIfExists('response_tables');
    }
}
