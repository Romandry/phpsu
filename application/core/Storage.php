<?php


/**
 * Storage
 *
 * Session storage class
 */

class Storage
{


    /**
     * $_data
     *
     * Session storage data pointer
     */

    private static $_data = array();


    /**
     * $_handler
     *
     * Session storage handler
     */

    private static $_handler = null;


    /**
     * setHandler
     *
     * Set session storage handler
     *
     * @param  string $handlerClassName Storage handler class name
     * @return null
     */

    public static function setHandler($handlerClassName)
    {
        self::$_handler = new $handlerClassName();
        session_set_save_handler(
            array(self::$_handler, 'open'   ),
            array(self::$_handler, 'close'  ),
            array(self::$_handler, 'read'   ),
            array(self::$_handler, 'write'  ),
            array(self::$_handler, 'destroy'),
            array(self::$_handler, 'gc'     )
        );

        if (round(((float) PHP_VERSION), 1) == 5.3) {
            register_shutdown_function('session_write_close');
        } else {
            session_register_shutdown();
        }
    }


    /**
     * init
     *
     * Initialization of session storage
     *
     * php.ini used to have session.gc_probability=0 with the comment:
     *
     * "This is disabled in the Debian packages,
     * due to the strict permissions on /var/lib/php5".
     * The strict permissions remain, but session.gc_probability is now enabled.
     *
     * By default there's a 0.1% chance that a call to session_start()
     * will trigger this, but setting session.gc_divisor=1
     * makes this easily reproducible.
     *
     * http://somethingemporium.com/2007/06/obscure-error-with-php5-on-debian-ubuntu-session-phpini-garbage
     *
     * And this use: @ session_start();
     *
     * @return null
     */

    public static function init()
    {
        if (!App::isCLI()) {
            if (self::$_handler) {
                register_shutdown_function('session_write_close');
            }
            session_name(App::getConfig('main')->system->session_name);
            @ session_start();
            self::_setSessionPointer($_SESSION);
        }
    }


    /**
     * read
     *
     * Read data from storage with key
     *
     * @param  string $key Data key
     * @return mixed       Exists stored data or null
     */

    public static function read($key)
    {
        return array_key_exists($key, self::$_data)
            ? self::$_data[$key]
            : null;
    }


    /**
     * write
     *
     * Save data into storage
     *
     * @param  string $key  Data key
     * @param  mixed  $data Stored data
     * @return null
     */

    public static function write($key, $data)
    {
        self::$_data[$key] = $data;
    }


    /**
     * remove
     *
     * Remove data from storage with key
     *
     * @param  string $key Data key
     * @return null
     */

    public static function remove($key)
    {
        if (array_key_exists($key, self::$_data)) {
            unset(self::$_data[$key]);
        }
    }


    /**
     * isExists
     *
     * Return status of exists data in storage
     *
     * @param  string $key Data key
     * @return bool        Status of exists data
     */

    public static function isExists($key)
    {
        return array_key_exists($key, self::$_data);
    }


    /**
     * clear
     *
     * Clear all session data,
     * storage reinitialization
     *
     * @return null
     */

    public static function clear()
    {
        self::$_data = array();
    }


    /**
     * _setSessionPointer
     *
     * Set inner session pointer
     *
     * @param  mixed $sessionPointer  Pointer of session resource
     * @return null
     */

    private static function _setSessionPointer( & $sessionPointer)
    {
        self::$_data = & $sessionPointer;
    }
}
