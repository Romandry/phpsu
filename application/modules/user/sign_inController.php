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
     * indexAction
     *
     * Index action of sign in controller user module
     *
     * @return null
     */

    public function indexAction()
    {
        // add language
        \View::addLanguageItem('signInController');
        // append breadcrumbs
        \common\BreadCrumbs::appendItem(
            // add current item
            new \common\BreadCrumbsItem(null, \View::$language->sign_in_title)
        );
        // assign data into view
        \View::assign('title', \View::$language->sign_in_title);
        // set output layout
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
                'title'         => \View::$language->sign_in_form_error,
                'description'   => \View::$language->sign_in_form_error_description,
                'form_messages' => $signInForm->getMessages()
            ));
        }
        // sign in
        helpers\SignInHelper::trySignIn($signInForm->getData());
    }
}
