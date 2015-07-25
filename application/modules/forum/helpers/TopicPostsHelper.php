<?php


/**
 * TopicPostsHelper
 *
 * Readonly topic posts helper
 */

namespace modules\forum\helpers;

class TopicPostsHelper
{


    /**
     * getPostsByTopicId
     *
     * Will return array of posts by identification number of topic
     *
     * @param  int   $topicID Identification number of topic
     * @param  int   $offset  Offset number of first post
     * @param  int   $limit   Number of posts per page
     * @return array          Posts data
     */

    public static function getPostsByTopicId($topicID, $offset, $limit)
    {
        $topicPosts = \DBI::getConnection('slave')->sendQuery(

            "SELECT

                    fp.id,
                    fp.topic_start,
                    fp.edited_by,
                    fp.moderated_by,
                    fp.creation_date,
                    fp.last_modified,
                    IF(fp.creation_date = fp.last_modified, 0, 1) is_modified,
                    fp.post_html,

                    fm.posts_count author_posts_count,

                    a.id     author_id,
                    a.login  author_login,
                    a.avatar author_avatar,
                    ag.name  author_group_name

                FROM forum_posts fp
                INNER JOIN forum_members fm
                    ON fm.author_id = fp.authored_by
                LEFT JOIN members a
                    ON a.id = fm.author_id
                LEFT JOIN groups ag
                    ON ag.id = a.group_id
                WHERE fp.topic_id = :topic_id
                ORDER BY fp.topic_start DESC, fp.creation_date ASC
                LIMIT {$offset}, {$limit}
            ",
            array(':topic_id' => $topicID)

        )->fetchAll(\PDO::FETCH_OBJ);

        $hCnf = \App::getConfig('hosts');
        $mCnf = \App::getConfig('member-defaults');
        foreach ($topicPosts as $item) {
            if (!$item->author_avatar) {
                $item->author_avatar = '//' . $hCnf->st . $mCnf->avatar;
            }
        }

        return $topicPosts;
    }
}
