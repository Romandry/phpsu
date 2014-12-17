<?php


/**
 * mainController
 *
 * Main controller (local bootstrap) of user module
 */

namespace modules\user;

class mainController extends \BaseController
{


    /**
     * indexAction
     *
     * Index action of main controller user module
     *
     * @return null
     */

    public function indexAction()
    {
        throw new \SystemErrorException(array(
            'title'       => 'User module error',
            'description' => 'Try access to indexAction'
        ));
    }


    /**
     * sign_outAction
     *
     * Global member logout
     *
     * @return null
     */

    public function sign_outAction()
    {
        \Member::signOut();
        \Request::redirect('/');
    }
}
