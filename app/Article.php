<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $table = "articles";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'logo', 'status', 
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
        return $this->hasMany('App\ArticleContent','articles_id', 'id');       
    }
    
    public function categorys(){
        
        return $this->belongsToMany('App\ArtCategory', 'arts_cats', 'article_id', 'category_id');
    }
}
