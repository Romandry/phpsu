<?php


/**
 * TrackerTopicsHelper
 *
 * Readonly tracker topics helper
 */

namespace modules\forum\helpers;

class TrackerTopicsHelper
{


    /**
     * $_filters
     *
     * Definition of available tracker filters
     */

    private static $_filters = array(
        'last'    => 'ORDER BY ft.last_modified DESC',
        'answers' => 'ORDER BY fts.posts_count ASC, ft.last_modified DESC',
        'posts'   => 'ORDER BY fts.posts_count DESC',
        'views'   => 'ORDER BY fts.views_count DESC'
    );


    /**
     * getTopics
     *
     * Will return topics data
     *
     * @param  string   $filterType Type of filter
     * @param  int      $offset     Offset number of first topic
     * @param  int      $limit      Number of topics per page
     * @return array                Topics data
     */

    public static function getTopics($filterType, $offset, $limit)
    {
        $filter = self::$_filters[$filterType];

        return \DBI::getConnection('slave')->sendQuery(
            "SELECT

                    ft.id,
                    ft.is_important,
                    ft.is_closed,
                    ft.title,
                    ft.description,

                    IF(fts.posts_count > 1, fts.posts_count - 1, 0) posts_count,
                    fts.views_count,
                    fts.last_post_id,

                    ft.authored_by,
                    ta.login author_login,

                    fp.authored_by   last_post_author_id,
                    fp.last_modified last_post_modified
,
                    pa.login         last_post_author_login

                FROM forum_topics ft
                INNER JOIN forum_topics_stat fts
                    ON fts.topic_id = ft.id
                LEFT JOIN forum_posts fp
                    ON fp.id = fts.last_post_id
                LEFT JOIN members ta
                    ON ta.id = ft.authored_by
                LEFT JOIN members pa
                    ON pa.id = fp.authored_by
                {$filter}
                LIMIT {$offset}, {$limit}"
        )->fetchAll(\PDO::FETCH_OBJ);
    }


    /**
     * getAllTopicsCount
     *
     * Will return number of all available topics
     *
     * @return int Number of all available topics
     */

    public static function getAllTopicsCount()
    {
        return (int) \DBI::getConnection('slave')->sendQuery(
            'SELECT SUM(topics_count) cnt FROM forum_subforums_stat'
        )->fetch(\PDO::FETCH_COLUMN);
    }
}
