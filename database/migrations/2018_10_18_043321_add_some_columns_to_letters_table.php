<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnsToLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->integer('sub_letter_code_id')->unsigned()->nullable()->after('letter_code_id');
            $table->integer('document_id')->unsigned()->nullable()->after('letter_code_id');
            $table->foreign('sub_letter_code_id')->references('id')->on('sub_letter_codes')
                    ->onDelete('cascade');
            $table->foreign('document_id')->references('id')->on('documents')
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
        Schema::table('letters', function (Blueprint $table) {
            $table->dropForeign(['document_id','sub_letter_code_id']);
            $table->dropColumn(['document_id','sub_letter_code_id']);
        });
    }
}
