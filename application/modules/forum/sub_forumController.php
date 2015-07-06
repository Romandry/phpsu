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
        $gsfData = $gsfForm->getData();

        // get limit settings
        $gsfData->limit = \App::getConfig('forum')->topics_per_page; // TODO topics per page from member custom settings
        // calculate offset
        $gsfData->offset = ($gsfData->page - 1) * $gsfData->limit;

        // get subforum
        $subForum = helpers\SubForumHelper::getSubForumById($gsfData->id);
        if (!$subForum) {
            throw new \MemberErrorException(array(
                'code'        => 404,
                'title'       => \View::$language->forum_sub_forum_error,
                'description' => \View::$language->forum_sub_forum_sub_forum_not_found
            ));
        }

        // get subforum topics
        $topics = helpers\SubForumTopicsHelper::getTopics(
            $gsfData->id,
            $gsfData->offset,
            $gsfData->limit
        );

        if ($gsfData->page > 1 && !$topics) {
            throw new \SystemErrorException(array(
                'title'       => \View::$language->forum_sub_forum_error,
                'description' => \View::$language->forum_sub_forum_page_not_found
            ));
        }
        // get posts pagination
        $pagination = array();
        if ($subForum->topics_count > $gsfData->limit) {
            $pagination = \common\PaginationHelper::getItems(
                '/forum/sub-forum?id=' . $subForum->id,
                $gsfData->page,
                $gsfData->limit,
                $subForum->topics_count
            );
        }

        // append breadcrumbs
        \common\BreadCrumbs::appendItems(
            array(
                // add forum item
                new \common\BreadCrumbsItem(
                    '/forum/forum?id=' . $subForum->forum_id,
                    $subForum->forum_title
                ),
                // add subforum (current) item
                new \common\BreadCrumbsItem(null, $subForum->title)
            )
        );

        // assign data into view
        \View::assign(
            array(
                'title'      => $subForum->title,
                'subForum'   => $subForum,
                'topics'     => $topics,
                'pagination' => $pagination
            )
        );
        // set output layout
        \View::setLayout('forum-sub-forum.phtml');
    }
}
