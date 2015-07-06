<?php


/**
 * mainController
 *
 * Main controller (local bootstrap) of main default module
 */

namespace modules\main;

class mainController extends \BaseController
{


    /**
     * indexAction
     *
     * Index action (home page) of main controller main module
     *
     * @return null
     */

    public function indexAction()
    {
        \View::addLanguageItem('mainMainController');
        \View::assign('title', \View::$language->main_main_title);
        \View::setLayout('main.phtml');
    }
}
