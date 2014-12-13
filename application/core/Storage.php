<?php


/**
 * Storage
 *
 * Session storage class
 */

class Storage
{


    /**
     * $_inSession
     *
     * Status of exists storage session data
     */

    private static $_inSession = true;


    /**
     * $_storageKey
     *
     * Global key of storage session data
     */

    private static $_storageKey = '__storage';


    /**
     * $_session
     *
     * Session compatible pointer
     */

    private static $_session = null;


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

        if (App::isCLI()) {
            self::$_session = array();
        } else {
            $config = App::getConfig('main')->system;
            session_name($config->session_name);
            @ session_start();
            session_regenerate_id();
            self::_setSessionPointer($_SESSION);
        }

        if (!array_key_exists(self::$_storageKey, self::$_session)) {
            self::$_inSession = false;
            self::clear();
        }

    }


    /**
     * inSession
     *
     * Returned status of exists storage session data
     *
     * @return bool Status of exists storage session data
     */

    public static function inSession()
    {
        return self::$_inSession;
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
        self::$_session[self::$_storageKey][$key] = $data;
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
        if (array_key_exists($key, self::$_session[self::$_storageKey])) {
            unset(self::$_session[self::$_storageKey][$key]);
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
        return array_key_exists($key, self::$_session[self::$_storageKey])
            ? self::$_session[self::$_storageKey][$key]
            : null;
    }


    /**
     * shift
     *
     * Read data from storage with key,
     * and unset it (like shift from stack)
     *
     * @param  string $key Data key
     * @return mixed       Exists stored data or null
     */

    public static function shift($key)
    {
        $data = self::read($key);
        self::remove($key);
        return $data;
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
        self::$_session = array();
        self::$_session[self::$_storageKey] = array();
    }


    /**
     * _setSessionPointer
     *
     * Set inner session pointer
     *
     * @param  mixed $sessionData Session data
     * @return null
     */

    private static function _setSessionPointer( & $sessionData)
    {
        self::$_session = & $sessionData;
    }
}
