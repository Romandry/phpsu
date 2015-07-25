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
        if (!$registerForm->isValid()) {
            throw new \MemberErrorException(array(
                'title'         => \View::$language->register_error,
                'description'   => \View::$language->register_proc_err_descr,
                'form_messages' => $registerForm->getMessages()
            ));
        }
        $userData = $registerForm->getData();

        // set new user defaults
        $hCnf = \App::getConfig('hosts');
        $mCnf = \App::getConfig('member-defaults');
        $pass = \common\CryptHelper::generateHash($userData->password);

        $userData->group_id        = $mCnf->group_id;
        $userData->password        = $pass;
        $userData->time_zone       = $mCnf->time_zone;
        $userData->status          = $mCnf->status;
        $userData->activation_hash = \common\HashHelper::getUniqueKey();
        $userData->avatar          = '//' . $hCnf->st . $mCnf->avatar;

        // create a new user
        $UserModel = \App::getInstance('common\UserModel');
        $UserModel->createUser($userData);

        // TODO send email notification of account activation

        \App::dump($userData);

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
