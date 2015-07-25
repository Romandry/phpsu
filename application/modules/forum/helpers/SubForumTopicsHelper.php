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
     * getTopics
     *
     * Will return topics data
     *
     * @param  int      $subForumID Identification number of subforum
     * @param  int      $offset     Offset number of first topic
     * @param  int      $limit      Number of topics per page
     * @return array                Topics data
     */

    public static function getTopics($subForumID, $offset, $limit)
    {
        return \DBI::getConnection('slave')->sendQuery(
            "SELECT

                    ft.id,
                    ft.creation_date,
                    ft.last_modified,
                    ft.is_locked,
                    ft.is_important,
                    ft.is_closed,
                    ft.title,
                    ft.description,

                    IF(fts.posts_count > 1, fts.posts_count - 1, 0) posts_count,
                    fts.views_count,
                    fts.last_post_id,

                    fp.last_modified last_post_modified
,
                    ta.id    author_id,
                    ta.login author_login,

                    pa.id    last_post_author_id,
                    pa.login last_post_author_login

                FROM forum_topics ft
                INNER JOIN forum_topics_stat fts
                    ON fts.topic_id = ft.id
                LEFT JOIN forum_posts fp
                    ON fp.id = fts.last_post_id
                LEFT JOIN members ta
                    ON ta.id = ft.authored_by
                LEFT JOIN members pa
                    ON pa.id = fp.authored_by
                WHERE ft.subforum_id = :subforum_id
                ORDER BY ft.is_locked DESC, ft.creation_date DESC
                LIMIT {$offset}, {$limit}
            ",
            array(':subforum_id' => $subForumID)
        )->fetchAll(\PDO::FETCH_OBJ);
    }
}
