<?php


/**
 * GetForum
 *
 * Forum request process validation form
 */

namespace modules\forum\forms;

class GetForum extends \common\Form
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
     * Forum request process validation class constructor.
     * Definition of validation rules
     *
     * @return null
     */

    public function __construct()
    {
        parent::__construct();

        $this->_rules['id'] = array(
            array('negation', 'IsNaturalNumber', null),
            array('filter',   'ToInt'),
            array('negation', 'GreatThanZero', null)
        );
    }
}
