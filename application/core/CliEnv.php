<?php


/**
 * CliEnv
 *
 * CLI mode environment builder
 * For example:
 * ~$ /usr/bin/php /path/do/htdocs/index.php -r[--request] /a/b/c?z=x&q=w -p[--post] r=1&s=2
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

        foreach ($argv as $k => $arg) {
            $next = $k + 1;
            if (($arg == '-r' || $arg == '--request') and array_key_exists($next, $argv)) {
                $_SERVER['REQUEST_URI'] = $argv[$next];
            }
        }
        if (!array_key_exists('REQUEST_URI', $_SERVER) or !$_SERVER['REQUEST_URI']) {
            throw new MemberErrorException(array(
                'title'       => 'CLI mode error',
                'description' => '--request argument not found or invalid'
            ));
        }

    }
}
