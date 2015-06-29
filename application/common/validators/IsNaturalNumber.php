<?php


/**
 * IsNaturalNumber
 *
 * IsNaturalNumber validator
 */

namespace common\validators;

class IsNaturalNumber
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
        return filter_var($value, FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_OCTAL);
    }
}
