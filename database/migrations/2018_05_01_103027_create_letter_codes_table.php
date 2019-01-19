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
        $tableName = 'letter_codes';
        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code')->comment('Kode Surat atau Sub Kode Surat');
            $table->string('title')->comment('Judul kode atau sub kode surat');
            $table->integer('letter_code_id')->unsigned()->nullable()->comment('Jika null berarti Sub Kode Surat; Relasi ke tabel sendiri');

            $table->foreign('letter_code_id')->references('id')->on('letter_codes')->onDelete('cascade');
            $table->unique(['code', 'letter_code_id']);
        });

        // DB::statement("ALTER TABLE `$tableName` comment 'Tabel untuk Kode dan Sub Kode Surat'");

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
