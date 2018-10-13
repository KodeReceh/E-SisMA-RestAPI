<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('letter_template_id')->unsigned();
            $table->integer('content_type');
            $table->foreign('letter_template_id')->references('id')->on('letter_templates')->onDelete('cascade');
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
        Schema::dropIfExists('letter_contents');
    }
}
