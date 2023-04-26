<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * this class used to check if the user has selected unique seat numbers.
 */
class checkUniqueSeatNumbersRule implements Rule
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return count($value) === count(array_unique($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sorry you select duplicated seat numbers, please select unique seat numbers.';
    }
}
