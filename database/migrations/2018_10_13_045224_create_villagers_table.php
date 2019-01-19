<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVillagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('villagers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('Nama Penduduk');
            $table->string('birthplace')->comment('Tempat Lahir');
            $table->date('birthdate')->comment('Tanggal Lahir');
            $table->tinyInteger('sex')->commnet('Jenis Kelamin');
            $table->string('job')->nullable()->comment('Pekerjaan');
            $table->tinyInteger('religion')->comment('Agama');
            $table->tinyInteger('tribe')->nullable()->comment('Suku');
            $table->string('NIK')->unique()->comment('NIK');
            $table->tinyInteger('status')->nullable()->comment('Status Kependudukan');
            $table->text('address')->comment('Alamat');
            $table->string('photo')->nullable()->comment('Foto');
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
        Schema::dropIfExists('villagers');
    }
}
