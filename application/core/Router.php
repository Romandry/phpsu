<?php


/**
 * Router
 *
 * Global router class
 */

class Router
{


    /**
     * $_params
     *
     * Route parameters
     */

    private static $_params = array();


    /**
     * pushParam
     *
     * Append route parameter
     *
     * @param string $param Route parameter
     * @return null
     */

    public static function pushParam($param)
    {
        self::$_params[] = $param;
    }


    /**
     * run
     *
     * Run route process
     *
     * @return null
     */

    public static function run()
    {

        // get module boot controller
        $moduleName = self::shiftParam();
        if ($moduleName == 'main') {
            throw new SystemErrorException(array(
                'title'       => 'Route error',
                'description' => 'Custom access is denied for main module'
            ));
        } else if ($moduleName === null) {
            $moduleName = 'main';
        }
        $nsPrefix = '\\modules\\' . $moduleName;
        $bootNs = $nsPrefix . '\\mainController';
        $moduleBoot = App::getInstance($bootNs);
        $moduleBoot->runBefore();

        // boot action or controller
        $ctrlName = self::shiftParam();
        if ($ctrlName === null) {
            $moduleBoot->indexAction();
        } else {

            // action of module boot controller
            $bootAction = $ctrlName;
            if ($bootAction == 'index') {
                throw new SystemErrorException(array(
                    'title'       => 'Execute action error',
                    'description' => 'Custom access is denied for indexAction'
                ));
            }
            $bootAction .= 'Action';

            if (method_exists($moduleBoot, $bootAction)) {
                // run action of module boot controller
                $moduleBoot->$bootAction();
            } else {

                // no, load controller of module
                $ctrlNs = $nsPrefix . '\\' . $ctrlName . 'Controller';
                $moduleCtrl = App::getInstance($ctrlNs);
                $moduleCtrl->runBefore();
                $ctrlAction = self::shiftParam();
                if ($ctrlAction == 'index') {
                    throw new SystemErrorException(array(
                        'title'       => 'Execute action error',
                        'description' => 'Custom access is denied for indexAction'
                    ));
                } else if ($ctrlAction === null) {
                    $ctrlAction = 'index';
                }
                $ctrlAction .= 'Action';

                // run action of controller
                if (!method_exists($moduleCtrl, $ctrlAction)) {
                    throw new SystemErrorException(array(
                        'title'       => 'Execute action error',
                        'description' => 'Action ' . $ctrlAction . ' not found'
                    ));
                }
                $moduleCtrl->$ctrlAction();
                $moduleCtrl->runAfter();

            }

        }
        $moduleBoot->runAfter();

    }


    /**
     * shiftParam
     *
     * Return first normalized parameter (or null) with remove inside
     *
     * @return mixed Route parameter
     */

    public static function shiftParam()
    {
        $param = null;
        if (self::$_params) {
            $param = self::_normalizeParam(array_shift(self::$_params));
        }
        return $param;
    }


    /**
     * _normalizeParam
     *
     * Return first normalized name of parameter
     *
     * @param  string $name Route parameter
     * @return mixed        Normalized route parameter
     */

    private static function _normalizeParam($name)
    {

        if ($name) {
            $blah = md5(microtime());
            $name = str_replace(
                array('__', '_', '.', '-'),
                array($blah, $blah, '__', '_'),
                $name
            );
        }
        return $name;

    }
}
