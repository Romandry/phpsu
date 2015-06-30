<?php


/**
 * StorageDatabaseHandler
 *
 * Custom session storage handler class
 */

class StorageDatabaseHandler
{


    /**
     * __construct
     *
     * Storage handler constructor
     *
     * @return null
     */

    public function __construct()
    {
        DBI::getConnection('master')->beginTransaction();
    }


    /**
     * open
     *
     * Open storage resource.
     * Nothing here, compatible only
     *
     * @param  string $savePath    Session save path
     * @param  string $sessionName Name of session
     * @return bool                Status
     */

    public function open($savePath, $sessionName)
    {
        return true;
    }


    /**
     * read
     *
     * Read data from session storage resource
     *
     * @param  string $id Session identificator
     * @return string     Serialized session data
     */

    public function read($id)
    {
        return (string) DBI::getConnection('master')->sendQuery(
            'SELECT `data` FROM session_data WHERE id = :id',
            array(':id' => $id)
        )->fetch(PDO::FETCH_COLUMN);
    }


    /**
     * write
     *
     * Write data into session storage resource
     *
     * @param  string $id   Session identificator
     * @param  string $data Serialized session data
     * @return bool         Status (always true)
     */

    public function write($id, $data)
    {
        DBI::getConnection('master')->sendQuery(
            'REPLACE INTO session_data (id, `data`, updated_by)
                VALUES (:id, :data, NOW())',
            array(
                ':id'   => $id,
                ':data' => $data
            )
        );

        return true;
    }


    /**
     * destroy
     *
     * Destroy session storage data
     *
     * @param  string $id Session identificator
     * @return bool       Status (always true)
     */

    public function destroy($id)
    {
        DBI::getConnection('master')->sendQuery(
            'DELETE FROM session_data WHERE id = :id',
            array(':id' => $id)
        );

        return true;
    }


    /**
     * gc
     *
     * Garbage collector.
     * Nothing here, compatible only
     *
     * @param  int  $maxlifetime Session max life time
     * @return bool              Status
     */

    public function gc($maxlifetime)
    {
        return true;
    }


    /**
     * close
     *
     * Close storage resource.
     * Nothing here, compatible only
     *
     * @return bool Status
     */

    public function close()
    {
        DBI::getConnection('master')->commit();
        return true;
    }
}
