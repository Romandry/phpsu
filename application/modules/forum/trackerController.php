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
        $gtfFilter = new forms\GetTrackerFilter();
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

        // get tracker topics by filter type
        $topics = helpers\TrackerTopicsHelper::getTopics(
            $gtfData->by,
            $gtfData->offset,
            $gtfData->limit
        );
        if ($gtfData->page > 1 && !$topics) {
            throw new \MemberErrorException(array(
                'code'        => 404,
                'title'       => \View::$language->forum_tracker_error,
                'description' => \View::$language->forum_tracker_page_not_found
            ));
        }

        // normalize tracker filter url
        $trackerUrl = '/forum/tracker';
        $filterUrl  = $trackerUrl;
        if ($gtfData->by != 'last') {
            $filterUrl = '/forum/tracker?by=' . $gtfData->by;
        }
        // get posts pagination
        $pagination   = array();
        $allTopicsCnt = helpers\TrackerTopicsHelper::getAllTopicsCount();
        if ($allTopicsCnt > $gtfData->limit) {
            $pagination = \common\PaginationHelper::getItems(
                $filterUrl,
                $gtfData->page,
                $gtfData->limit,
                $allTopicsCnt
            );
        }
        // normalize tracker filter title
        $filterTitle = 'forum_tracker_by_' . $gtfData->by . '_title';
        $filterTitle = \View::$language->{$filterTitle};
        // append breadcrumbs
        \common\BreadCrumbs::appendItems(
            array(
                new \common\BreadCrumbsItem(
                    $trackerUrl,
                    \View::$language->forum_tracker_breadcrumbs_name
                ),
                new \common\BreadCrumbsItem(null, $filterTitle)
            )
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
