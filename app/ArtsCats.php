<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArtsCats extends Model
{
    public $table = "arts_cats";
    
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
         'article_id', 'category_id', 
    ];
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
