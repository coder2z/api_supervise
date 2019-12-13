<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_tables', function (Blueprint $table) {
            $table->increments('id')->comment('请求表id');//请求表id
            $table->char('request_mode',100)->comment('请求方式');
            $table->json('params')->comment('请求参数');//请求参数
            $table->json('header')->comment('请求头');//请求头
            $table->integer('interface_id')->unsigned()->comment('外键interface_tables表主键id,设置restrict');
            $table->foreign('interface_id')//外键interface_tables表主键id,设置restrict
                    ->references('id')->on('interface_tables');

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
        Schema::dropIfExists('request_tables');
    }
}
