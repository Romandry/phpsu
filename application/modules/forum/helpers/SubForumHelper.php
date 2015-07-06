<?php


/**
 * SubForumHelper
 *
 * Readonly subforum helper
 */

namespace modules\forum\helpers;

class SubForumHelper
{


    /**
     * getSubForumById
     *
     * Will return subforum data
     *
     * @param  int   $subForumID Identification number of subforum
     * @return array             Subforum data
     */

    public static function getSubForumById($subForumID)
    {
        return \DBI::getConnection('slave')->sendQuery(
            'SELECT

                    sf.id,
                    sf.forum_id,
                    sf.title,
                    sf.description,

                    fss.topics_count,

                    ff.title forum_title

                FROM forum_subforums sf
                INNER JOIN forum_subforums_stat fss
                    ON fss.subforum_id = sf.id
                INNER JOIN forum_forums ff
                    ON ff.id = sf.forum_id
                WHERE sf.id = :subforum_id',
            array(':subforum_id' => $subForumID)
        )->fetch(\PDO::FETCH_OBJ);
    }
}
