<?php


/**
 * GreatThanZero
 *
 * GreatThanZero validator
 */

namespace common\validators;

class GreatThanZero
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
        return ($value > 0);
    }
}
