<?php


/**
 * to_postController
 *
 * To post jumping controller of forum module
 */

namespace modules\forum;

class to_postController extends \BaseController
{


    /**
     * indexAction
     *
     * Index action of to post jumping controller forum module
     *
     * @return null
     */

    public function indexAction()
    {
        \View::addLanguageItem('forumToPostController');

        // validate request params
        $gtpForm = new \modules\forum\forms\GetToPost();
        $gtpForm->validate();
        // invalid request params
        if (!$gtpForm->isValid()) {
            throw new \SystemErrorException(array(
                'title'       => \View::$language->forum_to_post_error,
                'description' => \View::$language->forum_to_post_request_invalid
            ));
        }

        // redirect to target post url
        $postID  = $gtpForm->getData()->id;
        $postUrl = helpers\ToPostHelper::getLinkByPostId($postID);
        \Request::redirect($postUrl);
    }
}
