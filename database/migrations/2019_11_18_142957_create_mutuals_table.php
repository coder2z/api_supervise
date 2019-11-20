<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMutualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutuals', function (Blueprint $table) {
            $table->increments('id')->comment('交互表主键id');//交互表主键id

            $table->integer('interface_id')->unsigned()->comment('外键interface_tables表主键id,设置restrict');
            $table->foreign('interface_id')//外键interface_tables表主键id,设置restrict
                    ->references('id')->on('interface_tables');

            $table->integer('front_uesr_id')->unsigned()->comment('外键user表主键id,设置restrict');
            $table->foreign('front_uesr_id')//外键user表主键id,设置restrict
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
        Schema::dropIfExists('mutuals');
    }
}
