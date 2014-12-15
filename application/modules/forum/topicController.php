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

        \View::setOutputContext('json');
        \View::assign('info', 'This is indexAction of \\modules\\forum\\topicController');

        $db = \Db::getConnection('slave');

        $stmt = $db->sendQuery('SHOW TABLES');
        \View::assign('tables', $stmt->fetchAll(\PDO::FETCH_ASSOC));

        $stmt = $db->sendQuery('SELECT * FROM information_schema.USER_PRIVILEGES');
        \View::assign('privileges', $stmt->fetchAll(\PDO::FETCH_ASSOC));

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
