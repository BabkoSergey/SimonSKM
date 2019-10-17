<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            
            $table->increments('id');
            
            $table->unsignedInteger('user_id')->nullable();    
            $table->integer('order_parent')->unsigned()->nullable();            
            $table->text('from');	
            $table->text('to');	                                    
            $table->text('price');            
            $table->text('discounts')->nullable();            
            $table->enum('order_type', ['payment', 'reparation', 'refund'])->default('payment');
            $table->enum('order_status', ['new', 'processed', 'closed', 'rejected'])->default('new');            
            $table->enum('payment_type', ['online', 'cash'])->default('online');
            $table->enum('payment_status', ['paid online', 'paid cash', 'not paid'])->default('not paid');            
            $table->dateTime('transaction')->nullable();	            
            
            $table->unsignedInteger('trailer_id')->nullable();    
            
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');            
            $table->foreign('trailer_id')->references('id')->on('trailers')->onDelete('set null');                        
            $table->foreign('order_parent')->references('id')->on('orders');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
