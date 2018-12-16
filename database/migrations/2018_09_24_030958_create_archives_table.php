<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('Nama Arsip');
            $table->date('date')->comment('Tanggal Arsip');
            $table->integer('archive_type_id')->unsigned()->comment('ID Tipe Arsip');
            $table->string('description')->comment('Keterangan Arsip');
            $table->foreign('archive_type_id')->references('id')->on('archive_types')->onDelete('cascade');
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
        Schema::dropIfExists('archives');
    }
}
