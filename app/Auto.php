<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auto extends Model
{
    public $table = "autos";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'logo', 'model', 'brand', 'release', 'mileage', 'price', 'show', 'sale', 'ria', 'range',
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
        return $this->hasMany('App\AutoContent','autos_id', 'id');       
    }
}
