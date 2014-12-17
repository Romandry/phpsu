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
        \View::assign('pageH1', 'Вход в аккаунт');
        \View::setLayout('user-sign-in.phtml');
    }
}
