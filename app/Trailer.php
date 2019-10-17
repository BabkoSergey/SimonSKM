<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trailer extends Model
{
    public $table = "trailers";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'price'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    
    /**
     * One to Many relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function contents()
    {                
        return $this->hasMany('App\TrailerContent','trailers_id', 'id');       
    }
    
    /**
     * One to Many relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function orders()
    {                
        return $this->hasMany('App\Order','trailer_id', 'id');       
    }
    
    /**
     * One to Many relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function disables()
    {                
        return $this->hasMany('App\TrailerDisable','trailer_id', 'id');       
    }
}
