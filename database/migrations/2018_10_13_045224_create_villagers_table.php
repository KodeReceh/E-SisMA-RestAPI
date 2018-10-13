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
            $table->string('name');
            $table->string('birthplace');
            $table->date('birthdate');
            $table->string('job')->nullable();
            $table->integer('religion');
            $table->integer('tribe')->nullable();
            $table->string('NIK')->unique();
            $table->string('status')->nullable();
            $table->text('address');
            $table->string('photo')->nullable();
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
