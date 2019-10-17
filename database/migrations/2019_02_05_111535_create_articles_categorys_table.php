<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesCategorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_categorys', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('parent')->unsigned()->nullable();
            $table->foreign('parent')->references('id')->on('articles_categorys') ->onDelete('cascade');
            
            $table->string('logo')->nullable();            
            $table->boolean('status')->default(false);
            
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
        Schema::dropIfExists('articles_categorys');
    }
}
