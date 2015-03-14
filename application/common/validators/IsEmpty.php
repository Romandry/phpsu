<?php


/**
 * IsEmpty
 *
 * IsEmpty validator
 */

namespace common\validators;

class IsEmpty
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
        return !mb_strlen($value);
    }
}
