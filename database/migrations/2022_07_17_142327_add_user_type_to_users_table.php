<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserTypeToUsersTable extends Migration
{

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->enum('user_type', ['مندوب', 'صيدلي','representative','pharmacist'])->default('pharmacist');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('users');
        });
    }
}
