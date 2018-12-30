<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyLetterTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('letter_templates', function (Blueprint $table) {
            $table->json('data')->nullable()->comment('Data raw untuk men-generate Surat');
            $table->integer('status')->default(0)->comment('Status Raw Surat');
            $table->string('generated_file')->nullable()->comment('File Surat yang telah di-generate');
            $table->timestamps();
            $table->integer('template_id')->unsigned()->comment('ID Template Surat');
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('letter_templates', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn(['template_id', 'data', 'generated_file']);
            $table->dropTimestamps();
        });
    }
}
