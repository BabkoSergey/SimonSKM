<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_contents', function (Blueprint $table) {
            
            $table->increments('id');
            
            $table->unsignedInteger('services_id')->index()->nullable();            
            $table->string('locale', 2);            
            $table->text('name');            
            $table->text('content')->nullable();            
            $table->string('url')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('meta')->nullable();
            
            $table->timestamps();
            
            $table->foreign('services_id')
                ->references('id')
                ->on('services')
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
        Schema::dropIfExists('services_contents');
    }
}
