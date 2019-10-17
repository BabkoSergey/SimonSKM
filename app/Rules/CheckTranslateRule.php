<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use File;

class CheckTranslateRule implements Rule
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
        $jsonString = [];
        if(File::exists(base_path('resources/lang/en.json'))){
            $jsonString = file_get_contents(base_path('resources/lang/en.json'));
            $jsonString = json_decode($jsonString, true);
        }        
        if (!array_key_exists($value, $jsonString)) {
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
        return __('Key already exists!');
    }
}
