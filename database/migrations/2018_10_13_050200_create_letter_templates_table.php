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
            $table->integer('villager_id')->unsigned()->nullable();
            $table->string('title');
            $table->integer('length_unit')->default(1);
            $table->integer('paper_size')->default(1);
            $table->integer('margin_left')->default(4);
            $table->integer('margin_top')->default(3);
            $table->integer('margin_right')->default(3);
            $table->integer('margin_bottom')->default(3);
            $table->integer('orientation')->default(1);
            $table->integer('font_family')->default(1);
            $table->foreign('villager_id')->references('id')->on('villagers')->onDelete('set null');
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
        Schema::dropIfExists('letter_templates');
    }
}
