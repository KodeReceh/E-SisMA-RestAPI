<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_documents', function (Blueprint $table) {
            $table->integer('letter_id')->unsigned();
            $table->integer('document_id')->unsigned();
            $table->string('description');

            $table->foreign('letter_id')->references('id')->on('letters')
                  ->onDelete('cascade');
            $table->foreign('document_id')->references('id')->on('documents')
                  ->onDelete('cascade');
            $table->unique(['letter_id', 'document_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('letter_documents');
    }
}
