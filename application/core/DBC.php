<?php


/**
 * DBC
 *
 * Database PDO connection wrapper class
 */

class DBC
{


    /**
     * $_PDO
     *
     * PDO connection object
     */

    private $_PDO = null;


    /**
     * $_isRollBack
     *
     * Rollback transaction status
     */

    private $_isRollBack = false;


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

        $options = array();
        foreach (array('host', 'port', 'dbname', 'charset') as $key) {
            $options[] = $key . '=' . $params->{$key};
        }

        try {

            $this->_PDO = new PDO(
                'mysql:' . join(';', $options),
                $params->user,
                $params->pass,
                // this is not working on Debian squeeze with MySQL 5.1.73-1
                // PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '{$params['charset']}'",
                array(PDO::ATTR_PERSISTENT => true)
            );
            $this->_PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_PDO->exec('SET NAMES \'' . $params->charset . '\'');

        } catch (PDOException $e) {
            throw new SystemErrorException(array(
                'title'       => 'Database error',
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

        try {
            DBI::addToStat($queryString);
            $stmt = $this->_PDO->prepare($queryString);
            $stmt->execute($queryParams);
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
        return $stmt;

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
     * getStat
     *
     * Return connection queries statistics
     *
     * @return array Statistics result
     */

    public function getStat()
    {
        return $this->_stat;
    }


    /**
     * close
     *
     * Close connection
     *
     * @return null
     */

    public function close()
    {
        if (!!$this->_PDO->inTransaction()) {
            $this->_PDO->rollBack();
        }
        $this->_PDO = null;
    }
}
