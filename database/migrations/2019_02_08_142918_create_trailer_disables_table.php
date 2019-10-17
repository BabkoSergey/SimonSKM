<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrailerDisablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trailer_disables', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('trailer_id');  
            
            $table->dateTime('from');	
            $table->dateTime('to');            
            $table->text('description')->nullable();            
            
            $table->timestamps();
            
            $table->foreign('trailer_id')->references('id')->on('trailers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trailer_disables');
    }
}
