<?php


/**
 * Email
 *
 * Email validator
 */

namespace common\validators;

class Email
{


    /**
     * isValid
     *
     * Return validation status
     *
     * @param  mixed $value Input data
     * @return bool         Validation status
     */

    public function isValid($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
