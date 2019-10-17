<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtsCategorysContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arts_categorys_contents', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('category_id')->index()->nullable();            
            $table->string('locale', 2);            
            $table->text('name');                        
            $table->string('url')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('meta')->nullable();
                        
            $table->foreign('category_id')
                ->references('id')
                ->on('articles_categorys')
                ->onDelete('cascade');
            
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
        Schema::dropIfExists('arts_categorys_contents');
    }
}
