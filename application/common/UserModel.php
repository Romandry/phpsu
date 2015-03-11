<?php


/**
 * UserModel
 *
 * Implementation of user model
 */

namespace common;

class UserModel
{


    /**
     * $_data
     *
     * User data
     */

    protected $_data = null;


    /**
     * __construct
     *
     * UserModel class constructor
     *
     * @param StdClass $data User data
     * @return null
     */

    public function __construct(\StdClass $data)
    {
        $this->_data = $data;
    }


    /**
     * save
     *
     * Save user data into database
     *
     * @return null
     */

    public function save()
    {
    }
}
