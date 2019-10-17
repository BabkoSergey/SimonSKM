<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckUniqueURLRule implements Rule
{
     public $massAttribute;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $massAttribute)
    {
        $this->massAttribute = $massAttribute;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $urls = array_column($this->massAttribute, 'url');
        foreach($urls as $url){
            if($url && count(array_keys($urls, $url)) > 1 )
                return false;
        }
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Friendly URL must must be different for all languages!');
    }
}
