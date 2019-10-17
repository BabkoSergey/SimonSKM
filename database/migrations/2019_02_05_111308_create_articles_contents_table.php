<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_contents', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('articles_id')->index()->nullable();            
            $table->string('locale', 2);            
            $table->text('name');            
            $table->text('content')->nullable();            
            $table->string('url')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('meta')->nullable();
            
            $table->timestamps();
            
            $table->foreign('articles_id')
                ->references('id')
                ->on('articles')
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
        Schema::dropIfExists('articles_contents');
    }
}
