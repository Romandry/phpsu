<?php


/**
 * BaseController
 *
 * Basic properties and methods of controller
 */

class BaseController
{


    /**
     * runBefore
     *
     * Run before action, only in/for extends
     *
     * @return null
     */

    public function runBefore()
    {
    }


    /**
     * runAfter
     *
     * Run after action, only in/for extends
     *
     * @return null
     */

    public function runAfter()
    {
    }


    /**
     * indexAction
     *
     * indexAction is required in/for extends
     *
     * @return null
     */

    public function indexAction()
    {
        throw new \SystemErrorException(array(
            'title'       => 'Route error',
            'description' => 'indexAction is required in your own controller'
        ));
    }
}
