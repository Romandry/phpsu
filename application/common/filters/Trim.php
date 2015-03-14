<?php


/**
 * Trim
 *
 * Strings trimmer
 */

namespace common\filters;

class Trim
{


    /**
     * run
     *
     * Run casting/filtering process
     *
     * @param  string $value    Input data
     * @param  string $charList Charlist for trimming
     * @return string           Output data
     */

    public function run($value, $charList = null)
    {
        return $charList ? trim($value, $charList) : trim($value);
    }
}
