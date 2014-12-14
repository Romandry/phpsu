<?php


/**
 * topicController
 *
 * Topic controller of forum module
 */

namespace modules\forum;

class topicController extends \BaseController
{


    /**
     * indexAction
     *
     * Index action of topic controller forum module
     *
     * @return null
     */

    public function indexAction()
    {
        \App::dump('This is indexAction of \\modules\\forum\\topicController');
    }


    /**
     * testAction
     *
     * Test action of topic controller forum module
     *
     * @return null
     */

    public function testAction()
    {

        $params = array();
        while ($param = \Router::shiftParam()) {
            $params[] = $param;
        }

        \App::dump(
            'This is testAction of \\modules\\forum\\topicController',
            'You can get more params: ' . join(', ', $params)
        );

    }
}
