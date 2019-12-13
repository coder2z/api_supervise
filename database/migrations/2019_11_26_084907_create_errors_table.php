<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('errors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('error_code')->unsigned();
            $table->text('error_info');
            $table->integer('http_code')->unsigned();
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
        Schema::dropIfExists('errors');
    }
}
