<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{    
    use SoftDeletes;
    
    public $table = "orders";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'user_id', 'order_parent', 'from', 'to', 'price', 'discounts', 'order_type', 'order_status', 'payment_type', 'payment_status', 'transaction', 'trailer_id'
    ];
        
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    
    /**
     * Soft Delete.
     *
     * @var array
     */
        
    protected $dates = [
        'deleted_at'
    ];
        
    /**
     * One to one relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function user()
    {                
        return $this->hasOne('App\User','id', 'user_id');
    }
    
    /**
     * One to one relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function trailer()
    {                
        return $this->hasOne('App\Trailer','id', 'trailer_id');
    }
    
    /**
     * One to one relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function soft()
    {                
        return $this->hasOne('App\OrderSoft','order_id', 'id');
    }
    
    
    public function nearestChild(){
        return $this->hasMany( 'App\Order', 'order_parent', 'id' );
    }
        
    public function nearestParent(){
        return  $this->hasOne( 'App\Order', 'id', 'order_parent' );
    }
    
}
