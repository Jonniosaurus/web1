<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
			$table->string('wrapper_id')->nullable();
			$table->string('wrapper_class')->nullable();
			$table->integer('order');
			$table->mediumText('content');
            $table->integer('page_id')->unsigned()->nullable();
            $table->foreign('page_id')->references('id')->on('pages');
            $table->integer('def_id')->unsigned()->nullable();
            $table->foreign('def_id')->references('id')->on('defs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contents');
    }
}
