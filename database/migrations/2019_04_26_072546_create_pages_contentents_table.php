<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesContententsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_contentents', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('pages_id')->index()->nullable();            
            $table->string('locale', 2);            
            $table->text('name');            
            $table->text('content')->nullable();                        
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('meta')->nullable();
            
            $table->timestamps();
            
            $table->foreign('pages_id')
                ->references('id')
                ->on('pages')
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
        Schema::dropIfExists('pages_contentents');
    }
}
