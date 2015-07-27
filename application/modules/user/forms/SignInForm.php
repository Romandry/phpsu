<?php


/**
 * SignInForm
 *
 * User sign-in form validation
 */

namespace modules\user\forms;

class SignInForm extends \common\Form
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
     * User sign in form class constructor.
     * Definition of validation rules
     *
     * @return null
     */

    public function __construct()
    {
        parent::__construct();
        \View::addLanguageItem('SignInForm');

        $this->_rules = array(
            'login' => array(
                array(
                    'negation',
                    'IsString',
                    \View::$language->sign_in_form_data_invalid
                ),
                array('filter', 'ToString'),
                array('filter', 'Trim'),
                array(
                    'assertion',
                    'IsEmpty',
                    \View::$language->sign_in_form_login_is_empty
                )
            ),
            'password' => array(
                array(
                    'negation',
                    'IsString',
                    \View::$language->sign_in_form_data_invalid
                ),
                array('filter', 'ToString'),
                array('filter', 'Trim'),
                array(
                    'assertion',
                    'IsEmpty',
                    \View::$language->sign_in_form_password_is_empty
                )
            )
        );

        // increment sign in tries number
        if (\Storage::isExists('sign-in-tries')) {
            $signInTries = \Storage::read('sign-in-tries') + 1;
        } else {
            $signInTries = 1;
        }
        \Storage::write('sign-in-tries', $signInTries);
        // protection code appendix
        if ($signInTries >= 3) { // TODO maybe get number of tries from config?
            $this->_rules['protection_code'] = array(
                array(
                    'negation',
                    'IsString',
                    \View::$language->sign_in_form_data_invalid
                ),
                array('filter', 'ToString'),
                array('filter', 'Trim'),
                array(
                    'assertion',
                    'IsEmpty',
                    \View::$language->sign_in_form_protection_code_is_empty
                )
            );
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

        // compare protection code
        if (property_exists($this->_data, 'protection_code')) {
            $protectionCode = \Storage::read('protection-code-sign-in');
            \Storage::remove('protection-code-sign-in');
            if ($this->_data->protection_code !== $protectionCode) {
                $this->addMessage(
                    'protection_code',
                    \View::$language->sign_in_form_protection_code_invalid
                );
            }
        }
        // remove sign in counter data
        if ($this->isValid()) {
            \Storage::remove('sign-in-tries');
        }
    }
}
