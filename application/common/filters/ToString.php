<?php


/**
 * ToString
 *
 * Cast data to string
 */

namespace common\filters;

class ToString
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
        return (string) $value;
    }
}
