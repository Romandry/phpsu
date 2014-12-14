<?php


/**
 * mainController
 *
 * Main controller (local bootstrap) of forum module
 */

namespace modules\forum;

class mainController extends \BaseController
{


    /**
     * indexAction
     *
     * Index action of main controller forum module
     *
     * @return null
     */

    public function indexAction()
    {
        \App::dump('This is indexAction of \\modules\\forum\\mainController');
    }
}
