<?php


/**
 * GetTrackerFilter
 *
 * Forum tracker request process validation form
 */

namespace modules\forum\forms;

class GetTrackerFilter extends \common\Form
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

        // append unrequired filter type
        if (\Request::getParam('by') !== null) {
            $this->_rules['by'] = array(
                array('negation',  'IsString', null),
                array('filter',    'ToString'      ),
                array('filter',    'Trim'          ),
                array('assertion', 'IsEmpty',  null)
            );
        } else {
            $this->_data->by = 'last';
        }

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


    /**
     * validate
     *
     * Run validation process
     *
     * @return null
     */

    public function validate()
    {
        parent::validate();

        $availableFilters = \App::getConfig('forum')->tracker_filters;
        if (!in_array($this->_data->by, $availableFilters, true)) {
            $this->_isValid = false;
        }
    }
}
