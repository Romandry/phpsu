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
                \View::$language->forum_main_breadcrumbs_name
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
        // validate request params
        $gfForm = new \modules\forum\GetForumForm();
        $gfForm->validate();
        // invalid request params
        if (!$gfForm->isValid()) {
            throw new \SystemErrorException(array(
                'title'       => \View::$language->forum_main_error,
                'description' => \View::$language->forum_main_request_invalid
            ));
        }
        // get request data
        $forumID = $gfForm->getData()->id;

        // get forums tree
        $forumsTree = ForumsTreeHelper::getTree($forumID);
        if ($forumID) {
            if (!$forumsTree) {
                throw new \SystemErrorException(array(
                    'title'       => \View::$language->forum_main_error,
                    'description' => \View::$language->forum_main_forum_not_found
                ));
            } else {
                // append breadcrumbs
                \common\BreadCrumbs::appendItem(
                    new \common\BreadCrumbsItem(
                        '/forum/sub-forum?id=' . $forumsTree[0]->id,
                        $forumsTree[0]->title
                    )
                );
            }
        }

        // assign data into view
        \View::assign('forumsTree',$forumsTree);
        // set output layout
        \View::setLayout('forum-main.phtml');
    }
}
