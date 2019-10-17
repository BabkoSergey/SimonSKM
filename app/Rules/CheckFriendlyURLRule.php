<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckFriendlyURLRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if (preg_match("#^[aA-zZ0-9\-_]+$#",$value)) {            
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Friendly URL must contain only A-Za-z0-9_- characters!');
    }
}
