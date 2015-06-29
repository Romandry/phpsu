<?php


/**
 * ToInt
 *
 * Cast data to integer
 */

namespace common\filters;

class ToInt
{


    /**
     * run
     *
     * Run casting/filtering process
     *
     * @param  mixed $value Input data
     * @return string       Output data
     */

    public function run($value)
    {
        return (int) $value;
    }
}
