<?php


/**
 * topicController
 *
 * Topic controller of forum module
 */

namespace modules\forum;

class topicController extends \BaseController
{


    /**
     * indexAction
     *
     * Index action of topic controller forum module
     *
     * @return null
     */

    public function indexAction()
    {
        \View::addLanguageItem('forumTopicController');

        // validate request params
        $gtForm = \App::getInstance('\modules\forum\GetTopicForm');
        $gtForm->validate();
        // invalid request params
        if (!$gtForm->isValid()) {
            throw new \SystemErrorException(array(
                'title'       => \View::$language->forum_topic_error,
                'description' => \View::$language->forum_topic_request_invalid
            ));
        }
        // get request data
        $gtData = $gtForm->getData();

        // get limit settings
        $gtData->limit = 10; // TODO posts per page from forum(default)/member(custom) settings
        // calculate offset
        $gtData->offset = ($gtData->page - 1) * $gtData->limit;

        // get topic
        $topic = TopicHelper::getTopicById($gtData->id);
        if (!$topic) {
            throw new \MemberErrorException(array(
                'code'        => 404,
                'title'       => \View::$language->forum_topic_error,
                'description' => \View::$language->forum_topic_topic_not_found
            ));
        }

        // get topic posts
        $postsData = TopicPostsHelper::getPostsByTopicId(
            $gtData->id,
            $gtData->offset,
            $gtData->limit
        );
        if (!$postsData['posts']) {
            $description = $gtData->page == 1
                ? \View::$language->forum_topic_first_post_not_found
                : \View::$language->forum_topic_page_not_found;
            throw new \SystemErrorException(array(
                'title'       => \View::$language->forum_topic_error,
                'description' => $description
            ));
        }
        // get posts pagination
        $pagination = array();
        if ($postsData['postsCount'] > $gtData->limit) {
            $pagination = \common\PaginationHelper::getItems(
                '/forum/topic?id=' . $topic->id,
                $gtData->page,
                $gtData->limit,
                $postsData['postsCount']
            );
        }

        // append breadcrumbs
        \common\BreadCrumbs::appendItems(
            array(
                // add forum item
                new \common\BreadCrumbsItem(
                    '/forum?id=' . $topic->forum_id,
                    $topic->forum_title
                ),
                // add subforum item
                new \common\BreadCrumbsItem(
                    '/forum/sub-forum?id=' . $topic->subforum_id,
                    $topic->subforum_title
                ),
                // add topic (current) item
                new \common\BreadCrumbsItem(null, $topic->topic_title)
            )
        );

        // assign data into view
        \View::assign(array(
            'topic'              => $topic,
            'posts'              => $postsData['posts'],
            'postsCountOffset'   => $gtData->offset + 1,
            'pagination'         => $pagination
        ));
        // set output layout
        \View::setLayout('forum-topic.phtml');
    }
}
