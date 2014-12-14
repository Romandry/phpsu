<?php


namespace modules\main;

class mainController extends \BaseController
{


    public function indexAction()
    {

        \View::assign(array(
            'range' => range(0, 10),
            'more'  => 'More text'
        ));
        \View::setLayout('main.phtml');

    }


}
