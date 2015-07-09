<?php


/**
 * mainController
 *
 * Main controller (local bootstrap) of documentation module
 */

namespace modules\documentation;

class mainController extends \BaseController
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
        \View::addLanguageItem('documentationMainController');
        \common\BreadCrumbs::appendItem(
            new \common\BreadCrumbsItem(
                '/documentation',
                \View::$language->documentation_main_title
            )
        );
    }


    /**
     * indexAction
     *
     * Index action of main controller documentation module
     *
     * @return null
     */

    public function indexAction()
    {
        // TODO PHP.SU introduction of this PHP documentation mirror

        // assign data into view
        \View::assign(
            array(
                'title'   => \View::$language->documentation_main_title,
                'h1'      => \View::$language->documentation_main_title,
                'content' => '<p>Something here...</p>'
            )
        );
        // set output layout
        \View::setLayout('documentation.phtml');
    }
}
