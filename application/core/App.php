<?php


/**
 * App
 *
 * Global application class
 */

class App
{


    /**
     * $_isCLIMode
     *
     * CLI mode flag
     */

    private static $_isCLIMode = false;


    /**
     * $_useMemory
     *
     * Value of initial allowed memory
     */

    private static $_useMemory;


    /**
     * $_startTime
     *
     * Value of begin running time
     */

    private static $_startTime;


    /**
     * $_configs
     *
     * Configs objects array
     */

    private static $_configs = array();


    /**
     * $_instances
     *
     * Initialized instances
     */

    private static $_instances = array();


    /**
     * isCLI
     *
     * Return CLI mode status
     *
     * @return bool CLI mode status
     */

    public static function isCLI()
    {
        return self::$_isCLIMode;
    }


    /**
     * dump
     *
     * Dump checkpoint data for tests and exit
     *
     * @return null
     */

    public static function dump()
    {

        if (self::$_isCLIMode) {
            $prefix = '';
            $suffix = '';
        } else {
            $prefix = '<hr /><pre>';
            $suffix = '</pre><hr />';
        }

        foreach (func_get_args() as $item) {
            echo $prefix;
            var_dump($item);
            echo $suffix;
        }
        exit();

    }


    /**
     * getConfig
     *
     * Will returned config object with lazy loading
     *
     * @param  string   $name Name of configuration file
     * @return StdClass       Configuration object
     */

    public static function getConfig($name)
    {
        return array_key_exists($name, self::$_configs)
            ? self::$_configs[$name]
            : self::_loadConfig($name);
    }


    /**
     * run
     *
     * Run application
     *
     * @param  int   $useMemory Value of initial allowed memory
     * @param  int   $startTime Value of begin running time
     * @param  bool  $isCLI     CLI mode flag
     * @return null
     */

    public static function run($useMemory, $startTime, $isCLI)
    {

        self::$_useMemory = $useMemory;
        self::$_startTime = $startTime;
        self::$_isCLIMode = $isCLI;

        Storage::init();
        View::init();
        Request::init();
        Router::run();

    }


    /**
     * getInstance
     *
     * Return object instance
     *
     * @param  string $nameSpace Namespace of instance
     * @return mixed             Object instance
     */

    public static function getInstance($nameSpace)
    {

        if (!array_key_exists($nameSpace, self::$_instances)) {
            if (method_exists($nameSpace, '__construct')) {
                $args = array();
                foreach (func_get_args() as $k => $arg) {
                    if ($k > 0) {
                        $args[] = $arg;
                    }
                }
                $ref = new ReflectionClass($nameSpace);
                self::$_instances[$nameSpace] = $ref->newInstanceArgs($args);
            } else {
                self::$_instances[$nameSpace] = new $nameSpace;
            }
        }
        return self::$_instances[$nameSpace];

    }


    /**
     * _loadConfig
     *
     * Trying load and build config
     *
     * @param  string $name Name of configuration file
     * @return object       Configuration object
     */

    private static function _loadConfig($name)
    {

        $config = APPLICATION . 'config/' . $name . '.json';
        $errPrf = 'Configuration file ' . $config . ' ';
        if (!is_file($config)) {
            exit($errPrf . 'not found or don\'t have readable permission');
        } else if (!$configData = self::_loadJsonFile($config)) {
            exit($errPrf . 'is broken or have syntax error');
        }
        self::$_configs[$name] = $configData;
        return self::$_configs[$name];

    }


    /**
     * _loadJsonFile
     *
     * Load and parse json file
     *
     * @param  string $filePath Absolute path of configuration file
     * @return object           Configuration object
     */

    private static function _loadJsonFile($filePath)
    {
        $patterns = array('~/\*.+?\*/~s', '~\s+?//.+\r?\n~');
        $fileData = preg_replace($patterns, '', file_get_contents($filePath));
        return json_decode($fileData, false);
    }
}
