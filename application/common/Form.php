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
     * $_rules
     *
     * Array of chain filters/validators rules
     */

    protected $_rules = array();


    /**
     * $_type
     *
     * Validation type (GET or POST)
     */

    protected $_type = 'GET';


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
        $prevRule = null;
        // each fields
        foreach ($this->_rules as $fieldName => & $rules) {

            $value = $this->_type == 'POST'
                ? \Request::getPostParam($fieldName)
                : \Request::getParam($fieldName);

            // each field rules
            foreach ($rules as & $rule) {

                $ruleSize = sizeof($rule);
                if ($ruleSize < 2) {
                    continue;
                }

                // filtering/casting
                if ($rule[0] == 'filter') {

                    $filterNS = '\common\filters\\' . $rule[1];
                    $filter = \App::getInstance($filterNS);
                    if ($ruleSize > 2) {
                        $args = array_slice($rule, 2);
                        array_unshift($args, $value);
                        $ref = new \ReflectionMethod($filterNS, 'run');
                        $value = $ref->invokeArgs($filter, $args);
                    } else {
                        $value = $filter->run($value);
                    }

                // validation
                } else if ($rule[0] == 'negation' || $rule[0] == 'assertion') {

                    $isValid = true;
                    $validatorNS = '\common\validators\\' . $rule[1];
                    $validator = \App::getInstance($validatorNS);
                    if ($ruleSize > 3) {
                        $args = array_slice($rule, 2, -1);
                        array_unshift($args, $value);
                        $ref = new \ReflectionMethod($validatorNS, 'isValid');
                        $isValid = $ref->invokeArgs($validator, $args);
                    } else {
                        $isValid = $validator->isValid($value);
                    }
                    // invert to assertion
                    if ($rule[0] == 'assertion') {
                        $isValid = !$isValid;
                    }
                    // update status, append message and out of chain
                    if (!$isValid) {
                        $this->_isValid = false;
                        $this->_messages[] = array(
                            'field'       => $fieldName,
                            'description' => array_pop($rule)
                        );
                        break;
                    }

                // unknown item type of chain
                } else {
                    continue;
                }

            }
        }
    }
}
