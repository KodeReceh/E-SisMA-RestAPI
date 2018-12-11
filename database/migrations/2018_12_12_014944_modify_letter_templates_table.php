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
            $table->dropForeign(['villager_id']);
            $table->dropColumn([
                'length_unit',
                'paper_size',
                'margin_left',
                'margin_top',
                'margin_right',
                'margin_bottom',
                'orientation',
                'font_family',
                'villager_id',
                'title'
            ]);
            $table->json('data')->nullable()->after('id');
            $table->integer('status')->default(0)->after('id');
            $table->integer('letter_id')->unsigned()->nullable()->after('id');
            $table->integer('template_id')->unsigned()->after('id');
            
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreign('letter_id')->references('id')->on('letters')->onDelete('set null');

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
            $table->dropForeign(['template_id', 'letter_id']);
            $table->dropColumn(['template_id', 'letter_id', 'data']);
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
        });
    }
}
