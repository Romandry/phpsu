<?php


/**
 * Request
 *
 * Request environment, get and set headers, redirect, refresh
 */

class Request
{


    /**
     * $_headers
     *
     * Response http headers collection
     */

    private static $_headers = array();


    /**
     * sendHeaders
     *
     * Send all collected http headers
     *
     * @return null
     */

    public static function sendHeaders()
    {
        if (!App::isCLI()) {
            foreach (self::$_headers as $item) {
                header($item);
            }
        }
    }


    /**
     * addHeader
     *
     * Add more header into collection
     *
     * @return null
     */

    public static function addHeader($header)
    {
        self::$_headers[] = $header;
    }
}
