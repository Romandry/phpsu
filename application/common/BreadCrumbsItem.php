<?php


/**
 * BreadCrumbsItem
 *
 * Instance of breadcrums item
 */

namespace common;

class BreadCrumbsItem
{


    /**
     * $url
     *
     * Breadcrumbs item URL value
     */

    public $url = null;


    /**
     * $url
     *
     * Breadcrumbs item name
     */

    public $name = null;


    /**
     * __construct
     *
     * Breadcrumbs instance constructor
     *
     * @param  string $url  Breadcrumbs item URL value
     * @param  string $name Breadcrumbs item name
     * @return null
     */

    public function __construct($url, $name)
    {
        $this->url  = $url;
        $this->name = $name;
    }
}
