<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('Nama Field');
            $table->integer('template_id')->unsigned()->comment('ID Template Surat');
            $table->integer('type')->comment('Tipe Field; Text, Gambar, Data Penduduk, atau Tanda Tangan');
            $table->integer('user_id')->unsigned()->nullable()->comment('ID Pengguna penanda tangan jika tipe Field adalah Tanda Tangan');

            $table->unique(['name', 'template_id']);
            $table->unique(['template_id', 'user_id']);
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template_fields');
    }
}
