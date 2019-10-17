<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrailerDisable extends Model
{
        
    public $table = "trailer_disables";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'from', 'to', 'description', 'trailer_id', 
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
        
    /**
     * One to one relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function trailer()
    {                
        return $this->hasOne('App\Trailer','id', 'trailer_id');
    }
   
}
