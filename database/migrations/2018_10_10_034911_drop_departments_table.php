<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class DropDepartmentsTable extends Migration
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
            Schema::dropIfExists('departments');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }else{
            Schema::dropIfExists('departments');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('department_code')->unique();
            $table->string('department_name');
            $table->string('description');
        });
    }
}
