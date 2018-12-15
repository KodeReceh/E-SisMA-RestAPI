<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->string('title');
            $table->integer('letter_code_id')->unsigned()->nullable();

            $table->foreign('letter_code_id')->references('id')->on('letter_codes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('letter_codes');
    }
}
