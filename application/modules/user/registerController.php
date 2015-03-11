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
        $registerForm = \App::getInstance('modules\user\RegisterForm');
        if (!$registerForm->isValid()) {
            throw new \MemberErrorException(array(
                'title'       => 'Ошибка',
                'description' => 'Некорректно заполнена форма регистрации',
                'messages'    => $registerForm->getMessages()
            ));
        }

        // create a new user
        $newUser = \App::getInstance(
            'common\UserModel',
            $registerForm->getData()
        );
        $newUser->save();
        // redirect to complete page
        \Storage::write('__register_complete', true);
        throw new \MemberSuccessException(array(
            'title'       => 'Готово',
            'description' => 'Новый пользователь успешно создан',
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
        \Storage::remove('__register_complete');
        \View::assign('pageH1', 'Поздравляем с успешной регистрацией');
        \View::setLayout('user-register-complete.phtml');
    }
}
