<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_tables', function (Blueprint $table) {
            $table->increments('id')->comment('日志表主键id');//日志表主键id
            $table->integer('user_id')->unsigned()->comment('外键user表主键id,设置restrict');
            $table->foreign('user_id')//外键user表主键id,设置restrict
                    ->references('id')->on('users');
            $table->string('operation_type')->comment('操作类型');//操作类型
            $table->string('operation_object')->comment('操作对象');//操作对象
            $table->text('content')->comment('具体操作内容');//具体操作内容
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
        Schema::dropIfExists('log_tables');
    }
}
