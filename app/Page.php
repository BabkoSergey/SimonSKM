<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public $table = "pages";    
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'logo', 'url', 
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
        return $this->hasMany('App\PageContent','pages_id', 'id');       
    }
    
}
