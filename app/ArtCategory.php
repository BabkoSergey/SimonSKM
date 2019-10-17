<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArtCategory extends Model
{
    public $table = "articles_categorys";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'logo', 'status', 'parent', 
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
        return $this->hasMany('App\ArtCategoryContent','category_id', 'id');       
    }
    
    public function nearestChild(){
        return $this->hasMany( 'App\ArtCategory', 'parent', 'id' );
    }
    
    public function childs(){
       return $this->nearestChild()->with('childs');
    }

    public function nearestParent(){
        return  $this->hasOne( 'App\ArtCategory', 'id', 'parent' );
    }
        
    public function parents(){
        return $this->nearestParent()->with('parents');
    }
    
    public function articles(){
        
        return $this->belongsToMany('App\Article', 'arts_cats', 'category_id', 'article_id');
    }
    
}
