<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrailersContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trailers_contents', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('trailers_id')->index()->nullable();            
            $table->string('locale', 2);                        
            $table->text('spec')->nullable();            
            
            $table->timestamps();
            
            $table->foreign('trailers_id')
                ->references('id')
                ->on('trailers')
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
        Schema::dropIfExists('trailers_contents');
    }
}
