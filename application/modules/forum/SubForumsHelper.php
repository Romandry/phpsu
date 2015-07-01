<?php


/**
 * SubForumsHelper
 *
 * Readonly sub forums helper
 */

namespace modules\forum;

class SubForumsHelper
{


    /**
     * getSubForumsByForumId
     *
     * Will return array of subforums by identification number of forum
     *
     * @param  int   $forumID Identification number of forum
     * @return array          Subforums data
     */

    public static function getSubForumsByForumId($forumID)
    {
        $params = array();
        $filter = '';

        if ($forumID) {
            $params[':forum_id'] = $forumID;
            $filter = 'WHERE sf.forum_id = :forum_id';
        }

        return \DBI::getConnection('slave')->sendQuery(
            "SELECT
                    sf.id,
                    sf.forum_id,
                    sf.topics_count,
                    sf.posts_count,
                    sf.title,
                    sf.description,

                    fp.id            last_post_id,
                    fp.authored_by   last_post_author_id,
                    fp.last_modified last_post_modified,
                    ft.title         last_topic_title,
                    a.login          last_post_author_login

                FROM forum_subforums sf
                INNER JOIN forum_forums ff
                    ON ff.id = sf.forum_id
                LEFT JOIN forum_posts fp
                    ON fp.id = sf.last_post_id
                LEFT JOIN forum_topics ft
                    ON ft.id = fp.topic_id
                LEFT JOIN members a
                    ON a.id = fp.authored_by
                {$filter}
                ORDER BY ff.sort ASC, sf.sort ASC
            ",
            $params
        )->fetchAll(\PDO::FETCH_OBJ);
    }
}
