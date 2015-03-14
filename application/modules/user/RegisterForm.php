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
        \View::addLanguageItem('RegisterForm');

        $this->_rules = array(
            'email' => array(
                array(
                    'negation',
                    'IsString',
                    \View::$language->register_form_data_invalid
                ),
                array('filter', 'ToString'),
                array('filter', 'Trim'),
                array(
                    'assertion',
                    'IsEmpty',
                    \View::$language->register_form_email_is_empty
                ),
                array(
                    'negation',
                    'Email',
                    \View::$language->register_form_email_invalid
                )
            ),
            'login' => array(
                array(
                    'negation',
                    'IsString',
                    \View::$language->register_form_data_invalid
                ),
                array('filter', 'ToString'),
                array('filter', 'Trim'),
                array(
                    'assertion',
                    'IsEmpty',
                    \View::$language->register_form_login_is_empty
                )
            ),
            'password' => array(
                array(
                    'negation',
                    'IsString',
                    \View::$language->register_form_data_invalid
                ),
                array('filter', 'ToString'),
                array('filter', 'Trim'),
                array(
                    'assertion',
                    'IsEmpty',
                    \View::$language->register_form_password_is_empty
                )
            ),
            'confirm_password' => array(
                array(
                    'negation',
                    'IsString',
                    \View::$language->register_form_data_invalid
                ),
                array('filter', 'ToString'),
                array('filter', 'Trim'),
                array(
                    'assertion',
                    'IsEmpty',
                    \View::$language->register_form_password_confirm_is_empty
                )
            ),
            'protection_code' => array(
                array(
                    'negation',
                    'IsString',
                    \View::$language->register_form_data_invalid
                ),
                array('filter', 'ToString'),
                array('filter', 'Trim'),
                array(
                    'assertion',
                    'IsEmpty',
                    \View::$language->register_form_protection_code_is_empty
                )
            )
        );
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

        // compare protection code
        if (property_exists($this->_data, 'protection_code')) {
            $protectionCode = \Storage::read('protection-code-register');
            if ($this->_data->protection_code !== $protectionCode) {
                \Storage::remove('protection-code-register');
                $this->addMessage(
                    'protection_code',
                    \View::$language->register_form_protection_code_invalid
                );
            }
        }
        // compare password confirmation
        if (property_exists($this->_data, 'password')
            && property_exists($this->_data, 'confirm_password')
            && $this->_data->password !== $this->_data->confirm_password) {
            $this->addMessage(
                'confirm_password',
                \View::$language->register_form_password_confirm_mismatch
            );
        }
    }
}
