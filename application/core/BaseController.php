<?php


/**
 * BaseController
 *
 * Basic properties and methods of controller
 */

class BaseController
{


    /**
     * $_hasInternalRoutes
     *
     * Custom internal routes mode flag
     */

    protected $_hasInternalRoutes = false;


    /**
     * hasInternalRoutes
     *
     * Return status of custom internal routes mode, only in/for extends
     *
     * @return null
     */

    public function hasInternalRoutes()
    {
        return $this->_hasInternalRoutes;
    }


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
