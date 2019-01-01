<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('ID Pengguna');
            $table->string('name')->comment('Nama Pengguna');
            $table->integer('employee_id_number')->unique()->nullable()->default(null)->comment('Nomor induk pegawai');
            $table->string('birthplace')->comment('Tempat Lahir');
            $table->date('birthdate')->comment('Tanggal Lahir');
            $table->string('email')->unique()->comment('Email Pengguna; Unik');
            $table->string('password')->comment('Password Pengguna');
            $table->string('api_token')->nullable()->comment('Token client');
            $table->integer('sex')->comment('Jenis Kelamin; 1 = Laki-laki, 2 = Perempuan');
            $table->string('address')->comment('Alamat Pengguna');
            $table->string('handphone')->comment('Nomor HP Pengguna');
            $table->integer('status')->default(1)->comment('Status Pengguna; Aktif atau Tidak aktif');
            $table->string('signature')->nullable()->comment('File tanda tangan, dalam bentuk gambar disarankan PNG dengan latar transparan');
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
        Schema::dropIfExists('users');
    }
}
