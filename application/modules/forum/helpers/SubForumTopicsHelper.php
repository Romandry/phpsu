<?php


/**
 * SubForumTopicsHelper
 *
 * Readonly subforum topics helper
 */

namespace modules\forum\helpers;

class SubForumTopicsHelper
{


    /**
     * getTopicById
     *
     * Will return topic data
     *
     * @param  int      $subForumID Identification number of subforum
     * @return StdClass             Topics data
     */

    public static function getTopicsBySubForumId($subForumID)
    {
        return \DBI::getConnection('slave')->sendQuery(
            'SELECT

                    ft.id,
                    ft.title,
                    ft.description,

                    fts.posts_count,
                    fts.views_count,
                    fts.last_post_id,

                    fp.authored_by   last_post_author_id,
                    fp.last_modified last_post_modified
,
                    a.login          last_post_author_login

                FROM forum_topics ft
                INNER JOIN forum_topics_stat fts
                    ON fts.topic_id = ft.id
                LEFT JOIN forum_posts fp
                    ON fp.id = fts.last_post_id
                LEFT JOIN members a
                    ON a.id = fp.authored_by
                WHERE ft.subforum_id = :subforum_id
                ORDER BY ft.creation_date
            ',
            array(':subforum_id' => $subForumID)
        )->fetchAll(\PDO::FETCH_OBJ);
    }
}
