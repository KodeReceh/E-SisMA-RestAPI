<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DropUserDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(env('DB_CONNECTION') == 'mysql'){
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Schema::dropIfExists('user_departments');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }else{
            DB::statement("DROP TABLE if exists user_departments cascade;");
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('user_departments', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('department_id')->unsigned();
            $table->integer('status');

            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')
                  ->onDelete('cascade');
        });
    }
}
