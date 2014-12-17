<?php


/**
 * DBI
 *
 * Database connections interface
 */

class DBI
{


    /**
     * $_connections
     *
     * Pool of connections
     */

    private static $_connections = array();


    /**
     * $_stat
     *
     * Transactions (queries) statistics counter
     */

    private static $_stat = array('read' => 0, 'change' => 0);


    /**
     * initConnections
     *
     * Add (init) all used connections
     *
     * @return null
     */

    public static function initConnections()
    {
        self::$_connections[] = new DBC(App::getConfig('main')->db);
    }


    /**
     * getConnection
     *
     * Return connection object (instance of self)
     *
     * @param  string $key Key of connection instance
     * @return DBC             Database connection object
     */

    public static function getConnection($key = null)
    {

        if (!$key || sizeof(self::$_connections) == 1) {
            reset(self::$_connections);
            $key = key(self::$_connections);
        }
        if (!array_key_exists($key, self::$_connections)) {
            throw new SystemErrorException(array(
                'title'       => 'Database error',
                'description' => 'Connection ' . $key . ' is closed'
            ));
        }
        return self::$_connections[$key];

    }


    /**
     * closeConnection
     *
     * Close connection
     *
     * @param  string $key Key of connection instance
     * @return null
     */

    public static function closeConnection($key)
    {
        if (array_key_exists($key, self::$_connections)) {
            self::$_connections[$key]->close();
            unset(self::$_connections[$key]);
        }
    }


    /**
     * getStat
     *
     * Return queries statistics
     *
     * @return array Statistics result
     */

    public static function getStat()
    {
        return self::$_stat;
    }


    /**
     * addToStat
     *
     * Add query into statistics
     *
     * @param  string $queryString SQL query string
     * @return null
     */

    public static function addToStat($queryString)
    {
        $preg = '/^\s*(?:INSERT|REPLACE|UPDATE|DELETE|DROP)/i';
        $type = preg_match($preg, $queryString) ? 'change' : 'read';
        self::$_stat[$type] += 1;
    }
}
