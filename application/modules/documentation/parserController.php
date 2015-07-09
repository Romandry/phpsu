<?php


/**
 * parserController
 *
 * Parser controller of documentation module,
 * this controller working only in CLI mode
 *
 * Call it like:
 *
 * as view status:        ~$ /usr/bin/php /path/do/public_html/index.php --request /documentation/parser
 * as run parser process: ~$ /usr/bin/php /path/do/public_html/index.php --request /documentation/parser/run
 */

namespace modules\documentation;

class parserController extends \BaseController
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
        // only for CLI mode
        if (!\App::isCLI()) {
            throw new \SystemErrorException(array(
                'title'       => 'Documentation module error',
                'description' => 'Trying access to parser with is not CLI mode'
            ));
        }
    }


    /**
     * indexAction
     *
     * Index action of parser controller documentation module
     *
     * @return null
     */

    public function indexAction()
    {
        // TODO show parser status
        \View::assign(
            array(
                'Last updated'      => '2015-07-10 01:03:23',
                'Found differences' => mt_rand(0, 100)
            )
        );
    }


    /**
     * runAction
     *
     * Cron job action for parse and diff manual process
     *
     * @return null
     */

    public function runAction()
    {
        // TODO run parse and diff process
        \View::assign('Status', 'Found ' . mt_rand(0, 100) . ' differences');
    }
}
