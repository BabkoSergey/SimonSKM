<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value', 
    ];

    protected $casts = ['private' => 'integer'];

    /**
     * Cast setting value to int, if it's a boolean number.
     *
     * @param string $value
     * @return int|string
     */
    public function getValueAttribute($value)
    {
        if ($value === '0' || $value === '1') {
            return (int) $value;
        }

        return $value;
    }
    
}
