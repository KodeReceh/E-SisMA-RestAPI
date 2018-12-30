<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('villager_id')->unsigned()->nullable()->comment('ID Penduduk');
            $table->string('letter_name')->comment('Nama Raw Data Surat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('letter_templates');
    }
}
