<?php


/**
 * Form
 *
 * Form validation (not render!) parent
 */

namespace common;

class Form
{


    /**
     * $_isValid
     *
     * Validation status
     */

    protected $_isValid = true;


    /**
     * $_messages
     *
     * List of validation messages
     */

    protected $_messages = null;


    /**
     * $_data
     *
     * Received form data
     */

    protected $_data = null;


    /**
     * __construct
     *
     * Form validation class constructor
     *
     * @return null
     */

    public function __construct()
    {
        $this->reset();
    }


    /**
     * reset
     *
     * Reset (clear) validation state
     *
     * @return null
     */

    public function reset()
    {
        $this->_isValid  = true;
        $this->_messages = array();
        $this->_data     = new \StdClass();
    }


    /**
     * isValid
     *
     * Run validation process and return status
     *
     * @return bool Validation status
     */

    public function isValid()
    {
        $this->_validate();
        return $this->_isValid;
    }


    /**
     * getMessages
     *
     * Return list of validation messages
     *
     * @return array List of validation messages
     */

    public function getMessages()
    {
        return $this->_messages;
    }


    /**
     * getData
     *
     * Return filtered and sanitized form data
     *
     * @return StdClass Form data
     */

    public function getData()
    {
        return $this->_data;
    }


    /**
     * _validate
     *
     * Run validation process
     *
     * @return null
     */

    protected function _validate()
    {
    }
}
