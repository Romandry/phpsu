<?php


/**
 * TopicPostsHelper
 *
 * Readonly topic posts helper
 */

namespace modules\forum;

class TopicPostsHelper
{


    /**
     * getPostsByTopicId
     *
     * Will return array of posts by identification number of topic
     *
     * @param  int   $topicID    Identification number of topic
     * @param  int   $offset     Offset number of first post
     * @param  int   $limit      Number of posts per page
     * @return array             Array of posts data
     */

    public static function getPostsByTopicId($topicID, $offset, $limit)
    {
        $conn  = \DBI::getConnection('slave');
        $posts = $conn->sendQuery(

            "SELECT

                    p.id,
                    p.topic_start,
                    p.authored_by,
                    p.edited_by,
                    p.moderated_by,
                    p.creation_date,
                    p.last_modified,
                    IF(p.creation_date = p.last_modified, 0, 1) is_modified,
                    p.post_html,

                    a.id     author_id,
                    a.login  author_login,
                    a.avatar author_avatar,
                    ag.name  author_group_name,
                    (0)      author_posts_count

                FROM forum_posts p
                LEFT JOIN members a
                    ON a.id = p.authored_by
                LEFT JOIN groups ag
                    ON ag.id = a.group_id
                WHERE p.topic_id = :topic_id
                ORDER BY p.topic_start DESC, p.creation_date ASC
                LIMIT {$offset}, {$limit}
            ",
            array(':topic_id' => $topicID)

        )->fetchAll(\PDO::FETCH_OBJ);

        $authors = array();
        foreach ($posts as $post) {
            $authors[] = $post->authored_by;
        }
        if ($authors) {
            $authors = join(',', $authors);
            $postsCounts = $conn->sendQuery(
                "SELECT
                        authored_by,
                        COUNT(1) cnt
                    FROM forum_posts
                    WHERE authored_by IN({$authors})
                    GROUP BY authored_by"
            )->fetchAll(\PDO::FETCH_OBJ);
            foreach ($posts as $post) {
                foreach ($postsCounts as $item) {
                    if ($post->authored_by == $item->authored_by) {
                        $post->author_posts_count = $item->cnt;
                    }
                }
            }
        }

        return $posts;
    }
}
