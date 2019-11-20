<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id')->comment('职位表主键id');//职位表主键id
            $table->integer('user_id')->unsigned()->comment('外键user表主键id,设置restrict');
            $table->foreign('user_id')//外键user表主键id,设置restrict
                    ->references('id')->on('users');
            $table->char('position_code',2)->default('-1')->comment('职位代码 0前端 1后端 -1未规定');//职位代码 0前端 1后端 -1未规定
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
        Schema::dropIfExists('positions');
    }
}
