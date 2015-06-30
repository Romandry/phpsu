<?php

/**
 * Database PDO connection wrapper class
 */
class DBC
{
    /**
     * PDO connection object
     */
    private $_PDO = null;

    /**
     * Statistic connection object
     */
    private $_stat = null;

    /**
     * Class constructor
     *
     * @param  StdClass $params Connection parameters
     *
     * @throws SystemErrorException
     */
    public function __construct(StdClass $params)
    {
        // Set some basic values
        $_params = (object)array(
            'host'    => (isset($params->host)) ? $params->host : 'localhost',
            'port'    => (isset($params->port)) ? $params->port : '3306',
            'dbname'  => (isset($params->dbname)) ? $params->dbname : '',
            'charset' => (isset($params->charset)) ? strtolower($params->charset) : 'utf8',
            'user' => (isset($params->user)) ? $params->user : '',
            'pass' => (isset($params->pass)) ? $params->pass : ''
        );

        $options = array();
        foreach (array('host', 'port', 'dbname', 'charset') as $key) {
            $options[] = $key . '=' . $_params->{$key};
        }

        try {

            $this->_PDO = new PDO(
                'mysql:' . join(';', $options),
                $_params->user,
                $_params->pass,
                // this is not working on Debian squeeze with MySQL 5.1.73-1
                // PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'blahblah'",
                array(PDO::ATTR_PERSISTENT => true)
            );
            $this->_PDO->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            $this->_PDO->exec('SET NAMES \'' . $_params->charset . '\'');

        } catch (PDOException $e) {
            throw new SystemErrorException(array(
                'title'       => 'Database error',
                'description' => $e->getMessage()
            ));
        }
    }

    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        if (!$this->_PDO->inTransaction()) {
            $this->_PDO->beginTransaction();
        }
    }

    /**
     * Commit transaction
     */
    public function commit()
    {
        if ($this->_PDO->inTransaction()) {
            $this->_PDO->commit();
        }
    }

    /**
     * Rollback transaction
     */
    public function rollBack()
    {
        if ($this->_PDO->inTransaction()) {
            $this->_PDO->rollBack();
        }
    }

    /**
     * Execute prepared query and return result
     *
     * @param  string  $queryString   SQL query string
     * @param  array   $queryParams   Parameters for placeholders
     *
     * @return object  PDOStatement
     * @throws SystemErrorException
     */
    public function sendQuery($queryString, array $queryParams = array())
    {
        try {
            DBI::addToStat($queryString);
            $stmt = $this->_PDO->prepare($queryString);
            $stmt->execute($queryParams);
        } catch (PDOException $e) {
            if ($this->_PDO->inTransaction()) {
                $this->_PDO->rollBack();
            }
            throw new SystemErrorException(array(
                'title'       => 'Database error',
                'description' => $e->getMessage()
            ));
        }

        return $stmt;
    }

    /**
     * Return lastInsertId value
     *
     * @return integer lastInsertId value
     */
    public function lastInsertId()
    {
        return $this->_PDO->lastInsertId();
    }

    /**
     * Return connection queries statistics
     *
     * @return array Statistics result
     */
    public function getStat()
    {
        return $this->_stat;
    }

    /**
     * Close connection
     */
    public function close()
    {
        $this->_PDO = null;
    }
}
