<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_files', function (Blueprint $table) {
            $table->integer('letter_id')->unsigned();
            $table->integer('file_id')->unsigned();

            $table->foreign('letter_id')->references('id')->on('letters');
            $table->foreign('file_id')->references('id')->on('files');

            $table->unique(['letter_id', 'file_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('letter_files');
    }
}
