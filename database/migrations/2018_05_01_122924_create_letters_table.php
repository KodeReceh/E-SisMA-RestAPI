<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number');
            $table->date('date');
            $table->string('subject');
            $table->string('tendency');
            $table->string('attachments');
            $table->string('to');
            $table->integer('letter_code_id')->nullable()->unsigned()->default(null);
            $table->timestamps();

            $table->foreign('letter_code_id')
                  ->references('id')->on('letter_codes')
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
        Schema::drop('letters');
    }
}
