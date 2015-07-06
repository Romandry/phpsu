<?php


/**
 * trackerController
 *
 * Tracker controller of forum module
 */

namespace modules\forum;

class trackerController extends \BaseController
{


    /**
     * indexAction
     *
     * Index action of tracker controller forum module
     *
     * @return null
     */

    public function indexAction()
    {
        \View::addLanguageItem('forumTrackerController');

        // validate request params
        $gtfFilter = new \modules\forum\forms\GetTrackerFilter();
        $gtfFilter->validate();
        // invalid request params
        if (!$gtfFilter->isValid()) {
            throw new \SystemErrorException(array(
                'title'       => \View::$language->forum_tracker_error,
                'description' => \View::$language->forum_tracker_request_invalid
            ));
        }
        // get request data
        $gtfData = $gtfFilter->getData();

        // get limit settings
        $gtfData->limit = \App::getConfig('forum')->topics_per_page; // TODO topics per page from member custom settings
        // calculate offset
        $gtfData->offset = ($gtfData->page - 1) * $gtfData->limit;

        // get subforum topics
        $topics = array();/*helpers\SubForumTopicsHelper::getTopics(
            $gsfData->id,
            $gsfData->offset,
            $gsfData->limit
        );

        if ($gsfData->page > 1 && !$topics) {
            throw new \SystemErrorException(array(
                'title'       => \View::$language->forum_sub_forum_error,
                'description' => \View::$language->forum_sub_forum_page_not_found
            ));
        }*/

        // normalize tracker filter url
        $filterUrl = '/forum/tracker';
        if ($gtfData->by != 'last') {
            $filterUrl = '/forum/tracker?by=' . $gtfData->by;
        }
        // get posts pagination
        $pagination = array();
        if (!$topics) {
            $pagination = \common\PaginationHelper::getItems(
                $filterUrl,
                $gtfData->page,
                $gtfData->limit,
                100
            );
        }
        // normalize tracker filter title
        $filterTitle = 'forum_tracker_by_' . $gtfData->by . '_title';
        $filterTitle = \View::$language->{$filterTitle};
        // append breadcrumbs
        \common\BreadCrumbs::appendItem(
            new \common\BreadCrumbsItem(null, $filterTitle)
        );
        // assign data into view
        $filters = helpers\TrackerFilterLinksHelper::getLinks($gtfData->by);
        \View::assign(
            array(
                'title'      => $filterTitle,
                'filters'    => $filters,
                'topics'     => $topics,
                'pagination' => $pagination
            )
        );
        // set output layout
        \View::setLayout('forum-tracker.phtml');
    }
}
