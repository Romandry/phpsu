<?php


/**
 * manualController
 *
 * Manual controller of documentation module
 */

namespace modules\documentation;

class manualController extends \BaseController
{


    /**
     * runBefore
     *
     * Run before action
     *
     * @return null
     */

    public function runBefore()
    {
        \View::addLanguageItem('documentationManualController');
        \common\BreadCrumbs::appendItem(
            new \common\BreadCrumbsItem(
                '/documentation/manual',
                \View::$language->documentation_manual_title
            )
        );
    }


    /**
     * $_hasInternalRoutes
     *
     * Custom internal routes mode flag
     */

    protected $_hasInternalRoutes = true;


    /**
     * indexAction
     *
     * Index action of manual controller documentation module
     *
     * @return null
     */

    public function indexAction()
    {
        // TODO try something like: /documentation/manual/function/imagecreatefromjpeg
        $uri  = '/documentation/manual';
        $last = '';
        while ($part = \Router::shiftParam(\Router::DIRTY_SHIFT)) {
            $last = $part;
            $uri .= '/' . $part;
            \common\BreadCrumbs::appendItem(
                new \common\BreadCrumbsItem($uri, $part)
            );
        }

        // assign data into view
        \View::assign(
            array(
                'title'   => $last ? $last : \View::$language->documentation_manual_title,
                'h1'      => $last ? $last : \View::$language->documentation_manual_title,
                'content' => '<p>' . ($last ? $last : \View::$language->documentation_manual_title) . '</p>'
            )
        );
        // set output layout
        \View::setLayout('documentation.phtml');
    }
}
