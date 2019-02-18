<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomingLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incoming_letters', function (Blueprint $table) {
            $table->integer('letter_id')->unsigned()->primary()->comment('1-1; ID surat menjadi Primary Key');
            $table->string('sender')->comment('Pengirim Surat');
            $table->date('receipt_date')->comment('Tanggal terima surat');
            $table->integer('ordinal')->comment('Urutan Surat');
            $table->foreign('letter_id')->references('id')->on('letters')
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
        Schema::dropIfExists('incoming_letters');
    }
}
