<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutosContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autos_contents', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('autos_id')->index()->nullable();            
            $table->string('locale', 2);            
            $table->text('description')->nullable();
            $table->text('spec')->nullable();            
            
            $table->timestamps();
            
            $table->foreign('autos_id')
                ->references('id')
                ->on('autos')
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
        Schema::dropIfExists('autos_contents');
    }
}
