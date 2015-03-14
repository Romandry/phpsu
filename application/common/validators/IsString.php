<?php


/**
 * IsString
 *
 * IsString validator
 */

namespace common\validators;

class IsString
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
        return is_string($value);
    }
}
