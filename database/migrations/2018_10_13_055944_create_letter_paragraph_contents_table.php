<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterParagraphContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_paragraph_contents', function (Blueprint $table) {
            $table->integer('letter_content_id')->unsigned();
            $table->float('font_size')->default(11);
            $table->string('font_color')->default('#000000'); //default black
            $table->float('indent')->default(1);
            $table->float('line_space')->default(1.5);
            $table->text('text');
            $table->float('alignment')->default(1);
            $table->foreign('letter_content_id')->references('id')->on('letter_contents')->onDelete('cascade');
            $table->primary(['letter_content_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('letter_paragraph_contents');
    }
}
