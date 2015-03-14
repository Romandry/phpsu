<?php


/**
 * RegisterForm
 *
 * User registration form validation
 */

namespace modules\user;

class RegisterForm extends \common\Form
{


    /**
     * $_type
     *
     * Validation type (GET or POST)
     */

    protected $_type = 'POST';


    /**
     * __construct
     *
     * User registration form class constructor.
     * Definition of validation rules
     *
     * @return null
     */

    public function __construct()
    {
        parent::__construct();

        $this->_rules = array(
            'email' => array(
                array('negation',  'IsString', 'Некорректный формат данных'),
                array('filter',    'ToString'                              ),
                array('filter',    'Trim',                                 ),
                array('assertion', 'IsEmpty',  'Вы не указали E-mail адрес'),
                array('negation',  'Email',    'Некорректный E-mail адрес' )
            ),
            'login' => array(
                array('negation',  'IsString', 'Некорректный формат данных'     ),
                array('filter',    'ToString'                                   ),
                array('filter',    'Trim',                                      ),
                array('assertion', 'IsEmpty',  'Вы не указали Логин (псевдоним)')
            ),
            'password' => array(
                array('negation',  'IsString', 'Некорректный формат данных'),
                array('filter',    'ToString'                              ),
                array('filter',    'Trim',                                 ),
                array('assertion', 'IsEmpty',  'Вы не указали пароль'      )
            ),
            'confirm_password' => array(
                array('negation',  'IsString', 'Некорректный формат данных'),
                array('filter',    'ToString'                              ),
                array('filter',    'Trim',                                 ),
                array('assertion', 'IsEmpty',  'Вы не подтвердили пароль'  )
            ),
            'protection_code' => array(
                array('negation',  'IsString', 'Некорректный формат данных'),
                array('filter',    'ToString'                              ),
                array('filter',    'Trim',                                 ),
                array('assertion', 'IsEmpty',  'Вы не ввели защитный код'  )
            )
        );
    }
}
