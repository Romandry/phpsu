<?php


/**
 * AnnounceTopicsHelper
 *
 * Readonly announce topics helper
 */

namespace modules\forum\helpers;

class AnnounceTopicsHelper
{


    /**
     * getTopics
     *
     * Will return topics data
     *
     * @return array Topics data
     */

    public static function getTopics()
    {
        $forumCnf = \App::getConfig('forum');

        return TrackerTopicsHelper::getTopics(
            $forumCnf->tracker_filters[0],   // default filter
            0,                               // offset
            $forumCnf->announce_topics_limit // announce limit
        );
    }
}
