<?php


/**
 * TopicHelper
 *
 * Readonly topic helper
 */

namespace modules\forum;

class TopicHelper
{


    /**
     * getTopicById
     *
     * Will return topic data
     *
     * @param  int      $topicID Identification number of topic
     * @return StdClass          Topic data
     */

    public static function getTopicById($topicID)
    {
        return \DBI::getConnection('slave')->sendQuery(
            'SELECT

                    ft.id,
                    ft.subforum_id,
                    ft.title topic_title,

                    fts.posts_count,
                    fts.views_count,

                    sf.title subforum_title,
                    sf.forum_id,

                    ff.title forum_title

                FROM forum_topics ft
                INNER JOIN forum_topics_stat fts
                    ON fts.topic_id = ft.id
                INNER JOIN forum_subforums sf
                    ON sf.id = ft.subforum_id
                INNER JOIN forum_forums ff
                    ON ff.id = sf.forum_id
                WHERE ft.id = :topic_id
            ',
            array(':topic_id' => $topicID)
        )->fetch(\PDO::FETCH_OBJ);
    }
}
