<?php


/**
 * Db
 *
 * Database PDO wrapper class
 */

class Db
{


    /**
     * $_connections
     *
     * Pool of connections
     */

    private static $_connections = array();


    /**
     * $_defaultPoolKey
     *
     * Default connection key
     */

    private static $_defaultPoolKey = '__singleMode';


    /**
     * $_PDO
     *
     * PDO connection object
     */

    private $_PDO = null;


    /**
     * $_stmtCache
     *
     * Cache of before prepared statements
     */

    private $_stmtCache = array();


    /**
     * $_isRollBack
     *
     * Rollback transaction status
     */

    private $_isRollBack = false;


    /**
     * $_counter
     *
     * Transactions (queries) counter
     */

    private $_counter = array('read' => 0, 'change' => 0);


    /**
     * getConnection
     *
     * Return connection object (instance of self)
     *
     * @param  string $poolKey Key of connection instance
     * @return Db              Connection object
     */

    public static function getConnection($poolKey)
    {

        if (!$poolKey) {
            $poolKey = self::$_defaultPoolKey;
        }
        if (!array_key_exists($poolKey, self::$_connections)) {
            throw new SystemErrorException(array(
                'title'       => 'Db error',
                'description' => 'Connection ' . $poolKey . ' is closed'
            ));
        }
        return self::$_connections[$poolKey];

    }


    /**
     * addConnection
     *
     * Return connection object (instance of self)
     *
     * @param  string   $poolKey Key of connection instance
     * @param  StdClass $params  Connection parameters
     * @return Db                Connection object
     */

    public static function addConnection($poolKey, StdClass $params)
    {

        if (!$poolKey) {
            $poolKey = self::$_defaultPoolKey;
        }
        if (array_key_exists($poolKey, self::$_connections)) {
            self::$_connections[$poolKey]->_close();
        }
        self::$_connections[$poolKey] = new self($params);
        return self::$_connections[$poolKey];

    }


    /**
     * closeConnection
     *
     * Close connection
     *
     * @param  string $poolKey Key of connection instance
     * @return null
     */

    public static function closeConnection($poolKey)
    {
        if (!$poolKey) {
            $poolKey = self::$_defaultPoolKey;
        }
        if (array_key_exists($poolKey, self::$_connections)) {
            self::$_connections[$poolKey]->_close();
            unset(self::$_connections[$poolKey]);
        }
    }


    /**
     * makeParams
     *
     * Make PDOStatement placeholders parameters from array
     *
     * @param  array $input Input array
     * @return array        Output array
     */

    public static function makeParams(array $input)
    {
        $output = array();
        foreach ($input as $k => $v) {
            $output[':' . $k] = $v;
        }
        return $output;
    }


    /**
     * getAllStat
     *
     * Return queries statistics
     *
     * @return array Statistics result
     */

    public static function getAllStat()
    {

        $sum = array('read' => 0, 'change' => 0);
        foreach (self::$_connections as $conn) {
            $stat = $conn->getStat();
            $sum['read'] += $stat['read'];
            $sum['change'] += $stat['change'];
        }
        return $sum;

    }


    /**
     * __construct
     *
     * Connection constructor
     *
     * @param  StdClass $params Connection parameters
     * @return null
     */

    public function __construct(StdClass $params)
    {

        $defaults = array(
            'host'    => '127.0.0.1',
            'port'    => 3306,
            'user'    => 'root',
            'pass'    => '',
            'charset' => 'utf8'
        );

        $params = array_merge($defaults, (array) $params);
        if (!array_key_exists('dbname', $params)) {
            throw new SystemErrorException(array(
                'title'       => 'Database connection error',
                'description' => 'You not set dbname option'
            ));
        }

        $options = array();
        foreach (array('host', 'port', 'dbname', 'charset') as $key) {
            $options[] = $key . '=' . $params[$key];
        }

        try {

            $this->_PDO = new PDO(
                'mysql:' . join(';', $options),
                $params['user'],
                $params['pass'],
                array(
                    PDO::ATTR_PERSISTENT => true,
                    // this not working on Debian squeeze with MySQL 5.1.73-1:
                    // PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '{$params['charset']}'",
                )
            );
            $this->_PDO->exec('SET NAMES \'' . $params['charset'] . '\'');
            $this->_PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            throw new SystemErrorException(array(
                'title'       => 'Database connection error',
                'description' => $e->getMessage()
            ));
        }

    }


    /**
     * beginTransaction
     *
     * Begin transaction
     *
     * @return null
     */

    public function beginTransaction()
    {
        if (!$this->_PDO->inTransaction()) {
            $this->_PDO->beginTransaction();
        }
    }


    /**
     * commit
     *
     * Commit transaction
     *
     * @return null
     */

    public function commit()
    {
        if (!$this->_isRollBack && !!$this->_PDO->inTransaction()) {
            $this->_PDO->commit();
        }
    }


    /**
     * rollBack
     *
     * Rollback state
     *
     * @return null
     */

    public function rollBack()
    {
        if (!!$this->_PDO->inTransaction()) {
            $this->_PDO->rollBack();
        }
    }


    /**
     * sendQuery
     *
     * Return executed PDOStatement result object
     *
     * @param  string Query string
     * @param  array  Parameters for placeholders
     * @return        PDOStatement Result object
     */

    public function sendQuery($queryString, array $queryParams = array())
    {

        $preg = '/^\s*(?:INSERT|REPLACE|UPDATE|DELETE|DROP)/i';
        $type = preg_match($preg, $queryString) ? 'change' : 'read';
        $this->_counter[$type] += 1;

        $stmtKey = md5($queryString);
        if (array_key_exists($stmtKey, $this->_stmtCache)) {
            $this->_stmtCache[$stmtKey]->closeCursor();
        } else {
            $this->_stmtCache[$stmtKey] = $this->_PDO->prepare($queryString);
        }

        try {
            $this->_stmtCache[$stmtKey]->execute($queryParams);
        } catch (PDOException $e) {
            if (!!$this->_PDO->inTransaction()) {
                $this->_isRollBack = true;
                $this->_PDO->rollBack();
            }
            throw new SystemErrorException(array(
                'title'       => 'Database error',
                'description' => $e->getMessage()
            ));
        }
        return $this->_stmtCache[$stmtKey];

    }


    /**
     * lastInsertId
     *
     * Return lastInsertId value
     *
     * @return int lastInsertId value
     */

    public function lastInsertId()
    {
        return $this->_PDO->lastInsertId();
    }


    /**
     * _close
     *
     * Close current connection
     *
     * @return null
     */

    private function _close()
    {
        if (!!$this->_PDO->inTransaction()) {
            $this->_PDO->rollBack();
        }
        $this->_PDO = null;
    }
}
