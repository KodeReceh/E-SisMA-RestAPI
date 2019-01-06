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
            $table->string('number')->comment('Nomor Surat');
            $table->date('date')->comment('Tanggal Surat');
            $table->string('subject')->comment('Judul Surat');
            $table->string('tendency')->nullable()->comment('Perihal Surat');
            $table->string('attachments')->default(0)->comment('Banyak Lampiran Surat');
            $table->integer('letter_code_id')->nullable()->unsigned()->default(null)->comment('Kode atau Sub Kode Surat');
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
