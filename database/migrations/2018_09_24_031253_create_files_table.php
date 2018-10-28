<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->integer('letter_id')->unsigned()->nullable()->default(null);
            $table->integer('document_id')->unsigned();
            $table->string('caption');
            $table->integer('ordinal');
            $table->foreign('letter_id')->references('id')->on('letters')
                  ->onDelete('cascade');
            $table->foreign('document_id')->references('id')->on('documents')
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
        Schema::dropIfExists('files');
    }
}
