<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutoContent extends Model
{
    public $table = "autos_contents";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'autos_id', 'locale', 'spec', 'description', 
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
    public function auto()
    {                
        return $this->hasOne('App\Auto','id', 'autos_id');
    }
}
