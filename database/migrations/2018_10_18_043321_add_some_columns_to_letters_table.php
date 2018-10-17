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
            $table->integer('sub_sub_letter_code_id')->unsigned()->nullable()->after('letter_code_id');
            $table->integer('sub_letter_code_id')->unsigned()->nullable()->after('letter_code_id');
            $table->foreign('sub_sub_letter_code_id')->references('id')->on('sub_sub_letter_codes')
                    ->onDelete('cascade');
            $table->foreign('sub_letter_code_id')->references('id')->on('sub_letter_codes')
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
            $table->dropForeign(['sub_sub_letter_code_id', 'sub_letter_code_id']);
            $table->dropColumn(['sub_sub_letter_code_id', 'sub_letter_code_id']);
        });
    }
}
