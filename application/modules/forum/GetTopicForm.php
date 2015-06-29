<?php


/**
 * GetTopicForm
 *
 * Topic request process validation form
 */

namespace modules\forum;

class GetTopicForm extends \common\Form
{


    /**
     * $_type
     *
     * Validation type (GET or POST)
     */

    protected $_type = 'GET';


    /**
     * __construct
     *
     * Topic request process validation class constructor.
     * Definition of validation rules
     *
     * @return null
     */

    public function __construct()
    {
        parent::__construct();

        $this->_rules = array(
            'id' => array(
                array('negation', 'IsNaturalNumber', null),
                array('filter',   'ToInt'),
                array('negation', 'GreatThanZero', null)
            )
        );

        // append unrequired page number
        if (\Request::getParam('page') !== null) {
            $this->_rules['page'] = array(
                array('negation', 'IsNaturalNumber', null),
                array('filter',   'ToInt'),
                array('negation', 'GreatThanZero', null)
            );
        } else {
            $this->_data->page = 1;
        }
    }
}
