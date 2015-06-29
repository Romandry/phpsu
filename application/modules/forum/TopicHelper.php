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
                    t.id,
                    t.subforum_id,
                    t.title topic_title,
                    sf.title subforum_title,
                    sf.forum_id,
                    f.title forum_title
                FROM forum_topics t
                INNER JOIN forum_subforums sf
                    ON sf.id = t.subforum_id
                INNER JOIN forum_forums f
                    ON f.id = sf.forum_id
                WHERE t.id = :id
            ',
            array(':id' => $topicID)
        )->fetch(\PDO::FETCH_OBJ);
    }
}
