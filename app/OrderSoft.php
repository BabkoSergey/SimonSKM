<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderSoft extends Model
{
    public $table = "orders_soft";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'order_id', 'user_soft', 'price_soft', 'discounts_soft', 'trailer_soft'
    ];
        
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'key_transaction'
    ];
                
    /**
     * One to one relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function order()
    {                
        return $this->hasOne('App\Order','id', 'order_id');
    }
}
