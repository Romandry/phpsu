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


    /**
     * tryAction
     *
     * Try sign in user
     *
     * @return null
     */

    public function tryAction()
    {
        \View::setOutputContext('json');
        \View::lockOutputContext();

        if (!\Request::isPost()) {
            throw new \MemberErrorException(array(
                'title'       => 'Ошибка',
                'description' => 'Некорректный запрос'
            ));
        }

        $signInValidator = new \modules\user\SignInValidator();
        $signInValidator->validate();

        /*$signInValidator = \App::getInstance('\\common\\Validator');
        $signInValidator->validatePostParams(array(
            'login' => function($value) {
            }
        ));*/

        /*$x = array('a' => function() { return 'x'; });
        \View::assign('x', $x['a']());

        $login = \Request::getPostParam('login');
        $pass  = \Request::getPostParam('password');
        $protection = \Request::getPostParam('protection_image');*/
    }
}
