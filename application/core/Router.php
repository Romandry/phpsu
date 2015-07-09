<?php


/**
 * Router
 *
 * Global router class
 */

class Router
{


    /**
     * CLEAN_SHIFT
     *
     * Enable normalization flag
     */

    const CLEAN_SHIFT = 0;


    /**
     * DIRTY_SHIFT
     *
     * Disable normalization flag
     */

    const DIRTY_SHIFT = 1;


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

        // internal custom routes
        if ($moduleBoot->hasInternalRoutes()) {
            $moduleBoot->indexAction();
        // default behaviour, boot action or controller
        } else {

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

                // run action of module boot controller
                if (method_exists($moduleBoot, $bootAction)) {
                    $moduleBoot->$bootAction();
                // try load controller of module
                } else {

                    $ctrlNs = $nsPrefix . '\\' . $ctrlName . 'Controller';
                    $moduleCtrl = App::getInstance($ctrlNs);
                    $moduleCtrl->runBefore();

                    // internal custom routes
                    if ($moduleCtrl->hasInternalRoutes()) {
                        $moduleCtrl->indexAction();
                    // default behaviour, run action
                    } else {

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

                    }

                    $moduleCtrl->runAfter();

                }

            }

        }

        $moduleBoot->runAfter();
    }


    /**
     * shiftParam
     *
     * Return first normalized parameter (or null) with remove inside
     *
     * @param  bool  $shiftType Enable or disable parameter normalization
     * @return mixed            Route parameter
     */

    public static function shiftParam($shiftType = Router::CLEAN_SHIFT)
    {
        $param = null;
        if (self::$_params) {
            $param = array_shift(self::$_params);
            if ($shiftType === Router::CLEAN_SHIFT) {
                $param = self::_normalizeParam($param);
            }
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
