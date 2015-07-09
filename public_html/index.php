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
 * Autoload function
 *
 * @param  string $name Name of loaded target with(out) namespace
 * @return null
 */

spl_autoload_register(function($name) {
    $name = ltrim($name, '\\');
    if (strpos($name, '\\', 1)) {
        $name = str_replace('\\', '/', $name);
        $path = APPLICATION . $name . '.php';
        if (!is_file($path)) {
            throw new SystemErrorException(array(
                'title'       => 'Autoload error',
                'description' => 'File ' . $path . ' is not exists'
            ));
        }
    } else {
        $path = $name . '.php';
    }
    require $path;
}, false);


// run application
try {

    $isCLI = PHP_SAPI == 'cli';
    App::init($useMemory, $startTime, $isCLI);
    Member::beforeInit();
    View::init();
    if ($isCLI) {
        CliEnv::init($argv);
    }
    App::run();

} catch (Exception $e) {
    View::assignException($e);
}
View::draw();
