<?php


/**
 * ToPostHelper
 *
 * To post jimping helper
 */

namespace modules\forum\helpers;

class ToPostHelper
{


    /**
     * getLinkByPostId
     *
     * Return redirection link value
     *
     * @param  int    $postID Identification number of post
     * @return string         Redirection link
     */

    public static function getLinkByPostId($postID)
    {
        $conn = \DBI::getConnection('slave');

        // try get post data
        $postData = $conn->sendQuery(
            'SELECT
                    fp.id post_id,
                    ft.id topic_id
                FROM forum_posts fp
                INNER JOIN forum_topics ft
                    ON ft.id = fp.topic_id
                WHERE fp.id = :post_id',
            array(':post_id' => $postID)
        )->fetch(\PDO::FETCH_OBJ);
        if (!$postData) {
            throw new \SystemErrorException(array(
                'title'       => \View::$language->forum_to_post_error,
                'description' => \View::$language->forum_to_post_post_not_found
            ));
        }
        // try get post offset
        $postsOffset = $conn->sendQuery(
            'SELECT
                    COUNT(1) cnt
                FROM forum_posts
                WHERE topic_id = :topic_id
                    AND id <= :post_id',
            array(
                'topic_id' => $postData->topic_id,
                'post_id'  => $postData->post_id
            )
        )->fetch(\PDO::FETCH_COLUMN);
        // calculate page number
        $pageNumber = $postsOffset / \App::getConfig('forum')->posts_per_page; // TODO posts per page from member custom settings
        $pageNumber = ceil($pageNumber);
        // build link
        $link = '/forum/topic?id=' . $postData->topic_id;
        if ($pageNumber > 1) {
            $link .= '&page=' . $pageNumber;
        }
        $link .= '#topic-post-' . $postData->post_id;

        return $link;
    }
}
