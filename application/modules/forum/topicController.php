<?php


namespace modules\forum;

class topicController extends \BaseController
{


    public function indexAction()
    {
        \App::dump('This is indexAction of \\modules\\forum\\topicController');
    }


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
