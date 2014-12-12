<?php


/**
 * UnexpectedException
 *
 * Unexpected exception taker class
 */

class UnexpectedException
{


    /**
     * take
     *
     * Take exception
     *
     * @param  Exception $e       Exception object
     * @param  bool      $isDebug Debug mode flag
     * @return null
     */

    public static function take($e, $isDebug = false)
    {

        if ($e instanceof SystemException) {
            $report = $e->getReport();
            if ($isDebug) {
                App::dump($report);
            } else {
                echo 'Unexpected system ' . $report['type'] . ' exception inside catch context' . PHP_EOL;
            }
        } else {
            if ($isDebug) {
                App::dump($e->getMessage(), $e->getTrace());
            } else {
                echo 'Unexpected exception inside catch context' . PHP_EOL;
            }
        }

    }
}
