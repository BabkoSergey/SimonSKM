<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersSoftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_soft', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('order_id');    
            $table->text('user_soft')->nullable();            
            $table->text('price_soft')->nullable();
            $table->text('discounts_soft')->nullable();
            $table->text('key_transaction')->nullable();
            $table->text('trailer_soft')->nullable();
            
            $table->timestamps();
            
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_soft');
    }
}
