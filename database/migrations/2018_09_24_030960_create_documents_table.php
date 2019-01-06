<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id')->comment('ID Dokumen');
            $table->string('title')->comment('Nama Dokumen');
            $table->string('path')->comment('Path; Nama File Dokumen');
            $table->string('file_type')->nullable()->comment('Tipe File');
            $table->date('date')->comment('Tanggal Dokumen');
            $table->integer('archive_id')->nullable()->unsigned()->comment('ID Arsip');
            $table->string('description')->nullable()->comment('Keterangan');
            $table->integer('uploader_id')->unsigned()->comment('User pengupload');

            $table->foreign('archive_id')->references('id')->on('archives')
                  ->onDelete('cascade');
            $table->foreign('uploader_id')->references('id')->on('users')
                  ->onDelete('cascade');
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
        Schema::dropIfExists('documents');
    }
}
