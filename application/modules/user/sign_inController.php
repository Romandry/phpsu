<?php


/**
 * sign_inController
 *
 * User sign in controller of user module
 */

namespace modules\user;

class sign_inController extends \BaseController
{


    /**
     * runBefore
     *
     * Run before action
     *
     * @return null
     */

    public function runBefore()
    {
        \View::addLanguageItem('signInController');
    }


    /**
     * indexAction
     *
     * Index action of sign in controller user module
     *
     * @return null
     */

    public function indexAction()
    {
        \View::assign('title', \View::$language->sign_in_title);
        \View::setLayout('user-sign-in.phtml');
    }


    /**
     * processAction
     *
     * User sign in process
     *
     * @return null
     */

    public function processAction()
    {
        \View::setOutputContext('json');
        \View::lockOutputContext();

        // validate form
        $signInForm = new forms\SignInForm();
        $signInForm->validate();
        if (!$signInForm->isValid()) {
            throw new \MemberErrorException(array(
                'title'         => \View::$language->sign_in_error,
                'description'   => \View::$language->sign_in_proc_err_descr,
                'form_messages' => $signInForm->getMessages()
            ));
        }
        $signInData = $signInForm->getData();
    }
}
