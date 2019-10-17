<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrailerContent extends Model
{
    public $table = "trailers_contents";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'trailers_id', 'locale', 'spec',
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
        return $this->hasOne('App\Trailer','id', 'trailers_id');
    }
}
