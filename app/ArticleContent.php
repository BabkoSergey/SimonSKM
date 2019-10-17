<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleContent extends Model
{
    public $table = "articles_contents";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'articles_id', 'locale', 'name', 'content', 'url', 'title', 'description', 'meta', 
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
    public function article()
    {                
        return $this->hasOne('App\Article','id', 'articles_id');
    }
}
