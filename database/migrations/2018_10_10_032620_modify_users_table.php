<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('department_id')->unsigned()->nullable()->after('handphone');
            $table->integer('role_id')->unsigned()->nullable()->after('handphone');
            $table->integer('status')->default(1)->after('handphone');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id', 'role_id']);
            $table->dropColumn('department_id');
            $table->dropColumn('role_id');
            $table->dropColumn('status');
        });
    }
}
