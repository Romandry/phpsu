<?php


/**
 * TrackerFilterLinksHelper
 *
 * Tracker filter links helper
 */

namespace modules\forum\helpers;

class TrackerFilterLinksHelper
{


    /**
     * getLinks
     *
     * Return array of available filter links
     *
     * @param  string $currentFilter Current active filter name
     * @return array                 Array of available filter links
     */

    public static function getLinks($currentFilter)
    {
        $links = array();
        foreach (\App::getConfig('forum')->tracker_filters as $k => $name) {
            $title = \View::$language->{'forum_tracker_by_' . $name . '_title'};
            $url   = '/forum/tracker' . ($k ? '?by=' . $name : '');
            $links[] = (object) array(
                'current' => $currentFilter == $name,
                'url'     => $url,
                'title'   => $title
            );
        }

        return $links;
    }
}
