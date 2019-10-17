<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autos', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('logo')->nullable();  
            $table->string('model');  
            $table->string('brand'); 
            $table->decimal('release', 4, 0);            
            $table->decimal('mileage', 12, 0)->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('range', 2, 1)->default(0);
            $table->boolean('show')->default(false);
            $table->boolean('sale')->default(true);
            $table->boolean('ria')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autos');
    }
}
