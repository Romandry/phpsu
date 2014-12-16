<?php


/**
 * CLI mode for example:
 * ~$ /usr/bin/php /path/do/htdocs/index.php -r[--request] /a/b/c?z=x&q=w
 */


// memory usage and timer
$useMemory = memory_get_usage();
$startTime = microtime(true);

// errors reporting level
ini_set('display_errors', 'On');
ini_set('html_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// define application pathes
define('PUBLIC_HTML', dirname(__FILE__) . '/');
define('APPLICATION', realpath(PUBLIC_HTML . '../application') . '/');
define('UPLOADS',     realpath(PUBLIC_HTML . '../uploads_html') . '/');

// set global encoding value
mb_internal_encoding('UTF-8');


/**
 * set main environment pathes,
 * WARNING! now set pathes without default value of get_include_path()
 */

$pathes = array('core/', 'layouts/');
set_include_path(APPLICATION . join(PATH_SEPARATOR . APPLICATION, $pathes));


/**
 * mainAutoloader
 *
 * Autoload classes function
 *
 * @param  string $className with(out) namespace
 * @return null
 */

function mainAutoloader($className)
{

    $className = ltrim($className, '\\');
    if (strpos($className, '\\', 1)) {
        $className = str_replace('\\', '/', $className);
        $classPath = APPLICATION . $className . '.php';
        if (!is_file($classPath)) {
            throw new SystemErrorException(array(
                'title'       => 'Autoload error',
                'description' => 'File ' . $classPath . ' is not exists'
            ));
        }
    } else {
        $classPath = $className . '.php';
    }
    require $classPath;

}
spl_autoload_register('mainAutoloader', false);


// run application
try {

    $isCLI = PHP_SAPI == 'cli';
    if ($isCLI) {
        CliEnv::init($argv);
    }
    App::run($useMemory, $startTime, $isCLI);

} catch (Exception $e) {
    View::assignException($e);
}
View::draw();
