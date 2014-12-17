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

        $conn = \DBI::getConnection('slave');

        $stmt = $conn->sendQuery('SHOW TABLES');
        \View::assign('tables', $stmt->fetchAll(\PDO::FETCH_ASSOC));

        $stmt = $conn->sendQuery('SELECT * FROM information_schema.USER_PRIVILEGES');
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


    /**
     * test_exceptionAction
     *
     * JSON exception test action
     *
     * @return null
     */

    public function test_exceptionAction()
    {

        \View::setOutputContext('json');
        throw new \MemberErrorException(array(
            'title'       => 'Test error',
            'description' => 'Description of test error',
            'other_data'  => array(1,2,3,4,5,6)
        ));

    }


    /**
     * test_exception__xmlAction
     *
     * XML exception test action
     *
     * @return null
     */

    public function test_exception__xmlAction()
    {

        \View::setOutputContext('xml');
        throw new \MemberErrorException(array(
            'code'        => 404,
            'title'       => 'Test error',
            'description' => 'Description of test error',
            'other_data'  => array(1,2,3,4,5,6)
        ));

    }


    /**
     * test_exception__htmlAction
     *
     * HTML exception test action
     *
     * @return null
     */

    public function test_exception__htmlAction()
    {

        throw new \MemberErrorException(array(
            'title'       => 'Test error',
            'description' => 'Description of test error',
            'other_data'  => array(1,2,3,4,5,6)
        ));

    }
}
