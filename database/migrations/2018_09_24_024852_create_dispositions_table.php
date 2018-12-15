<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDispositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'dispositions';
        Schema::create($tableName, function (Blueprint $table) {
            $table->integer('incoming_letter_id')->unsigned();
            $table->integer('user_id')->unsigned();            
            $table->string('summary')->nullable();
            $table->date('processing_date')->nullable();
            $table->string('information')->nullable();
            $table->foreign('incoming_letter_id')->references('letter_id')->on('incoming_letters')
                  ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Tabel ini juga berperan sebagai recipient surat masuk'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispositions');
    }
}
