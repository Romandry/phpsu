<?php


/**
 * GetForumForm
 *
 * Forum request process validation form
 */

namespace modules\forum;

class GetForumForm extends \common\Form
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

        // forum id is unrequired
        if (\Request::getParam('id') !== null) {
            $this->_rules['id'] = array(
                array('negation', 'IsNaturalNumber', null),
                array('filter',   'ToInt'),
                array('negation', 'GreatThanZero', null)
            );
        } else {
            $this->_data->id = null;
        }
    }
}
