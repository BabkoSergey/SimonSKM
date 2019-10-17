<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    public $table = "pages_contentents";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'pages_id', 'locale', 'name', 'content', 'title', 'description', 'meta', 
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
    public function page()
    {                
        return $this->hasOne('App\Page','id', 'pages_id');
    }
}
