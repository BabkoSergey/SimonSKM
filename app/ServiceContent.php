<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceContent extends Model
{
    public $table = "services_contents";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'services_id', 'locale', 'name', 'content', 'url', 'title', 'description', 'meta', 
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
    public function service()
    {                
        return $this->hasOne('App\Service','id', 'services_id' );
    }
}
