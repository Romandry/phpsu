<?php


/**
 * forumController
 *
 * Forum controller of forum module
 */

namespace modules\forum;

class forumController extends \BaseController
{


    /**
     * indexAction
     *
     * Index action of forum controller forum module
     *
     * @return null
     */

    public function indexAction()
    {
        \View::addLanguageItem('forumForumController');

        // validate request params
        $gfForm = new \modules\forum\forms\GetForum();
        $gfForm->validate();
        // invalid request params
        if (!$gfForm->isValid()) {
            throw new \SystemErrorException(array(
                'title'       => \View::$language->forum_forum_error,
                'description' => \View::$language->forum_forum_request_invalid
            ));
        }

        // get forum tree
        $forum = helpers\ForumsTreeHelper::getTree($gfForm->getData()->id);
        if (!$forum) {
            throw new \SystemErrorException(array(
                'title'       => \View::$language->forum_forum_error,
                'description' => \View::$language->forum_forum_forum_not_found
            ));
        }

        // append breadcrumbs
        \common\BreadCrumbs::appendItem(
            new \common\BreadCrumbsItem(null, $forum[0]->title)
        );
        // assign data into view
        \View::assign(
            array(
                'title' => $forum[0]->title,
                'forum' => $forum[0]
            )
        );
        // set output layout
        \View::setLayout('forum-forum.phtml');
    }
}
