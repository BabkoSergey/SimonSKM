<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArtCategoryContent extends Model
{
    public $table = "arts_categorys_contents";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'category_id', 'locale', 'name', 'url', 'title', 'description', 'meta', 
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
    public function category()
    {                
        return $this->hasOne('App\ArtCategory','id', 'category_id');
    }
}
