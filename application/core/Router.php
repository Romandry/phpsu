<?php


/**
 * Router
 *
 * Route modules
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

    public static function pushParam($param) {
        self::$_params[] = $param;
    }
}
