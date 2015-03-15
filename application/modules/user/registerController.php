<?php


/**
 * registerController
 *
 * User registration controller of user module
 */

namespace modules\user;

class registerController extends \BaseController
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
        \View::addLanguageItem('registerController');
    }


    /**
     * indexAction
     *
     * Index action of registration controller user module
     *
     * @return null
     */

    public function indexAction()
    {
        \View::assign('title', \View::$language->register_title);
        \View::setLayout('user-register.phtml');
    }


    /**
     * processAction
     *
     * Member registration process
     *
     * @return null
     */

    public function processAction()
    {
        // set json context
        \View::setOutputContext('json');
        \View::lockOutputContext();

        // validate form
        $registerForm = \App::getInstance('\modules\user\RegisterForm');
        $registerForm->validate();
        // invalid form data
        if (!$registerForm->isValid()) {
            throw new \MemberErrorException(array(
                'title'         => \View::$language->register_error,
                'description'   => \View::$language->register_proc_err_descr,
                'form_messages' => $registerForm->getMessages()
            ));
        }
        // create a new user
        $newUser = \App::getInstance(
            'common\NewUserModel',
            $registerForm->getData()
        );
        $newUser->save();
        \App::dump($newUser);

        // redirect to complete page
        \Storage::write('__register_complete', true);
        throw new \MemberSuccessException(array(
            'redirection' => '/user/register/complete'
        ));
    }


    /**
     * completeAction
     *
     * Member registration complete page
     *
     * @return null
     */

    public function completeAction()
    {
        if (!\Storage::isExists('__register_complete')) {
            \Request::redirect('/');
        }
        //\Storage::remove('__register_complete');
        \View::assign('title', \View::$language->register_title_complete);
        \View::setLayout('user-register-complete.phtml');
    }
}
