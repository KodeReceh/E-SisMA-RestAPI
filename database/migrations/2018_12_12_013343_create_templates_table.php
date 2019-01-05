<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('Nama Template');
            $table->string('template_file')->comment('File Template; Dalam doc atau docx');
            $table->integer('needs_villager_data')->default(0)->comment('Apakah membutuhkan data penduduk?; 0=false, 1=true');
            $table->integer('letter_code_id')->unsigned()->comment('kode jenis surat');
            $table->timestamps();
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
        Schema::dropIfExists('templates');
    }
}
