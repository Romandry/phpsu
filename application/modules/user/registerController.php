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
     * indexAction
     *
     * Index action of register controller user module
     *
     * @return null
     */

    public function indexAction()
    {
        \View::assign('pageH1', 'Регистрация аккаунта');
        \View::setLayout('user-register.phtml');
    }
}
