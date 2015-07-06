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
     * runBefore
     *
     * Run before action
     *
     * @return null
     */

    public function runBefore()
    {
        \View::addLanguageItem('forumMainController');
        \common\BreadCrumbs::appendItem(
            new \common\BreadCrumbsItem(
                '/forum',
                \View::$language->forum_main_title
            )
        );
    }


    /**
     * indexAction
     *
     * Index action of main controller forum module
     *
     * @return null
     */

    public function indexAction()
    {
        // assign data into view
        \View::assign(
            array(
                'title'      => \View::$language->forum_main_title,
                'h1'         => \View::$language->forum_main_title,
                'forumsTree' => helpers\ForumsTreeHelper::getTree()
            )
        );
        // set output layout
        \View::setLayout('forum-main.phtml');
    }
}
