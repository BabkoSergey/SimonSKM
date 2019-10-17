<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersAdditionalInfoTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();            
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();            
            $table->boolean('status')->default(true);
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
            $table->dropColumn('phone');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');            
            $table->dropColumn('status');
        });
    }

}
