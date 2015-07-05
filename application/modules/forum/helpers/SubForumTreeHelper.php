<?php


/**
 * SubForumTreeHelper
 *
 * Readonly subforum tree helper
 */

namespace modules\forum\helpers;

class SubForumTreeHelper
{


    /**
     * getTree
     *
     * Will return subforum tree data
     *
     * @param  int   $subForumID Identification number of subforum
     * @return array             Subforum tree data
     */

    public static function getTree($subForumID)
    {
        // get forums
        $subForum = \DBI::getConnection('slave')->sendQuery(
            'SELECT
                    sf.id,
                    sf.forum_id,
                    sf.title,
                    sf.description,
                    (NULL) topics,

                    ff.title forum_title

                FROM forum_subforums sf
                INNER JOIN forum_forums ff
                    ON ff.id = sf.forum_id
                WHERE sf.id = :subforum_id',
            array(':subforum_id' => $subForumID)
        )->fetch(\PDO::FETCH_OBJ);

        // get topics
        if ($subForum) {
            $subForum->topics = SubForumTopicsHelper::getTopicsBySubForumId($subForumID);
        }

        return $subForum;
    }
}
