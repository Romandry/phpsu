<?php


/**
 * sub_forumController
 *
 * Subfum controller of forum module
 */

namespace modules\forum;

class sub_forumController extends \BaseController
{


    /**
     * indexAction
     *
     * Index action of subforum controller forum module
     *
     * @return null
     */

    public function indexAction()
    {
        \View::addLanguageItem('forumSubForumController');

        // validate request params
        $gsfForm = new \modules\forum\forms\GetSubForum();
        $gsfForm->validate();
        // invalid request params
        if (!$gsfForm->isValid()) {
            throw new \SystemErrorException(array(
                'title'       => \View::$language->forum_sub_forum_error,
                'description' => \View::$language->forum_sub_forum_request_invalid
            ));
        }
        // get request data
        $subForumID = $gsfForm->getData()->id;

        // get topics tree of subforum
        $subForumTree = helpers\SubForumTreeHelper::getTree($subForumID);
        if (!$subForumTree) {
            throw new \SystemErrorException(array(
                'title'       => \View::$language->forum_sub_forum_error,
                'description' => \View::$language->forum_sub_forum_sub_forum_not_found
            ));
        } else {
            // append breadcrumbs
            \common\BreadCrumbs::appendItems(
                array(
                    new \common\BreadCrumbsItem(
                        '/forum?id=' . $subForumTree->forum_id,
                        $subForumTree->forum_title
                    ),
                    new \common\BreadCrumbsItem(null, $subForumTree->title)
                )
            );
        }

        // assign data into view
        \View::assign('subForumTree', $subForumTree);
        // set output layout
        \View::setLayout('forum-sub-forum.phtml');
    }
}
