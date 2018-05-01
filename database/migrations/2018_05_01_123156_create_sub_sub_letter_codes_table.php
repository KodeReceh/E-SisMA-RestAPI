<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubSubLetterCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_sub_letter_codes', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('sub_sub_letter_code');
          $table->string('sub_sub_code_title');
          $table->integer('sub_letter_code_id')->unsigned();

          $table->foreign('sub_letter_code_id')
                ->references('id')->on('sub_letter_codes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_sub_letter_codes', function (Blueprint $table) {
            //
        });
    }
}
