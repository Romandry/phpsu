<?php


/**
 * CliEnv
 *
 * CLI mode environment builder
 * For example:
 * ~$ /usr/bin/php /path/do/htdocs/index.php -r[--request] /a/b/c?z=x&q=w
 */

class CliEnv
{


    /**
     * init
     *
     * Build CLI mode environment
     *
     * @param  array $argv Command line arguments
     * @return null
     */

    public static function init($argv)
    {

        View::setOutputContext('txt');
        View::lockOutputContext();

        $key = 'REQUEST_URI';
        foreach ($argv as $k => $arg) {
            if ($arg == '-r' || $arg == '--request') {
                $next = $k + 1;
                if (array_key_exists($next, $argv)) {
                    $_SERVER[$key] = $argv[$next];
                }
            }
        }
        if (!array_key_exists($key, $_SERVER) || !$_SERVER[$key]) {
            throw new MemberErrorException(array(
                'title'       => 'CLI mode error',
                'description' => '--request argument not found or invalid'
            ));
        }

    }
}
