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
     * $_rawUrl
     *
     * Origin request string with $_GET parameters
     */

    private static $_rawUrl = null;


    /**
     * $_url
     *
     * Request string without $_GET parameters
     */

    private static $_url = null;


    /**
     * $_params
     *
     * Stored $_GET parameters
     */

    private static $_params = array();


    /**
     * init
     *
     * Parse client request
     *
     * @return null
     */

    public static function init()
    {
        if (App::getConfig('main')->site->block_prefetch_requests) {
            self::_blockPrefetchRequest();
        }
        self::_preValidateRequest();


        /**
         * I don't like the combination of "/?" in the URL,
         * and I'm want after the "action" immediately "?"
         */

        self::$_rawUrl = preg_replace(
            '/([^\/=\?&]+)\/(\?)/',
            '$1$2',
            $_SERVER['REQUEST_URI']
        );
        self::$_rawUrl = rtrim(self::$_rawUrl, '/');
        if (!self::$_rawUrl) {
            self::$_rawUrl = '/';
        }
        if (self::$_rawUrl != $_SERVER['REQUEST_URI']) {
            self::redirect(self::$_rawUrl);
        }


        /**
         * clear REQUEST_URI value and GET array,
         * validate request string format,
         * get request parameters
         *
         * $mca - Module Controller Action
         * $gp  - GET parameters
         *
         * $parts['mca'] example: module/controller/action
         * $parts['gp'] example: agr1=val1&arg2&argN=valN
         */

        $_GET = array();
        $_SERVER['REQUEST_URI'] = '';

        $parts = array();
        $mca   = '\/(?P<mca>[^\/=\?&]+(?:(?:\/[^\/=\?&]+)+)?)?';
        $gp    = '(?:\?(?P<gp>[^\/=\?&]+(?:=[^\/=\?&]*)';
        $gp   .= '?(?:(?:&[^\/=\?&]+(?:=[^\/=\?&]*)?)+)?))?';

        if (!preg_match('/^' . $mca . $gp . '$/u', self::$_rawUrl, $parts)) {
            throw new SystemErrorException(array(
                'title'       => 'Request error',
                'description' => 'Broken query string format'
            ));
        }

        // autoredirection to first page without page parameter
        $wfpPattern = '/^(.+)(?:(?:page=1&(.+))|(?:\?|&)page=1$)/';
        $withoutFirstPage = preg_replace($wfpPattern, '$1$2', self::$_rawUrl);
        if ($withoutFirstPage != self::$_rawUrl) {
            self::redirect($withoutFirstPage);
        }

        // store route data
        if (!isset($parts['mca'])) {
            $parts['mca'] = '';
        }
        self::$_url = '/' . $parts['mca'];
        foreach (explode('/', $parts['mca']) as $param) {
            $param = rawurldecode($param);
            if ($param) {
                Router::pushParam($param);
            }
        }
        if (isset($parts['gp'])) {
            parse_str($parts['gp'], self::$_params);
        }
    }


    /**
     * redirect
     *
     * Moved permanently redirect
     *
     * @param  string $destination Destination URL
     * @return null
     */

    public static function redirect($destination)
    {
        self::$_headers = array();
        self::addHeader('HTTP/1.1 301 Moved Permanently');
        self::addHeader('Location: ' . $destination);
        self::sendHeaders();
        exit();
    }


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
     * @param  string $header HTTP header
     * @return null
     */

    public static function addHeader($header)
    {
        self::$_headers[] = $header;
    }


    /**
     * getClientInfo
     *
     * Get client information from headers
     *
     * @param  string Key (name) of client header
     * @return string Header value
     */

    public static function getClientInfo($key)
    {
        if ($key == 'IP') {
            $hcip = getenv('HTTP_CLIENT_IP');
            $hxff = getenv('HTTP_X_FORWARDED_FOR');
            $radd = getenv('REMOTE_ADDR');
            if ($hcip) {
                $ip = $hcip;
            } else if ($hxff) {
                $ip = $hxff;
            } else {
                $ip = false;
            }
            return (!$ip || $ip == 'unknown') ? $radd : $ip;
        } else {
            return array_key_exists($key, $_SERVER)
                ? $_SERVER[$key]
                : 'unknown';
        }
    }


    /**
     * getRawUrl
     *
     * Return raw origin URL
     *
     * @return string Raw origin URL
     */

    public static function getRawUrl()
    {
        return self::$_rawUrl;
    }


    /**
     * getUrl
     *
     * Return URL without $_GET parameters
     *
     * @return string URL without $_GET parameters
     */

    public static function getUrl()
    {
        return self::$_url;
    }


    /**
     * getParam
     *
     * Will return value of $_GET parameter or default value
     *
     * @param  string $key     Key of GET parameter
     * @param  mixed  $default Default value
     * @return mixed           Value
     */

    public static function getParam($key, $default = null)
    {
        return array_key_exists($key, self::$_params)
            ? self::$_params[$key]
            : $default;
    }


    /**
     * getPostParam
     *
     * Will return value of $_POST parameter or default value
     *
     * @param  string $key     Key of POST parameter
     * @param  mixed  $default Default value
     * @return mixed           Value
     */

    public static function getPostParam($key, $default = null)
    {
        return ($_POST && array_key_exists($key, $_POST))
            ? $_POST[$key]
            : $default;
    }


    /**
     * isPost
     *
     * Return status of request POST type
     *
     * @return bool Status of request POST type
     */

    public static function isPost()
    {
        return !!$_POST;
    }


    /**
     * _blockPrefetchRequest
     *
     * Block prefetch client request
     *
     * @return null
     */

    private static function _blockPrefetchRequest()
    {
        $x = 'HTTP_X_MOZ';
        if (array_key_exists($x, $_SERVER) && $_SERVER[$x] == 'prefetch') {
            self::$_headers = array();
            $gmDate = gmdate('D, d M Y H:i:s');
	        self::addHeader('HTTP/1.1 403 Prefetching Forbidden');
	        self::addHeader('Expires: Thu, 21 Jul 1977 07:30:00 GMT');
	        self::addHeader('Last-Modified: ' . $gmDate . ' GMT');
	        self::addHeader('Cache-Control: post-check=0, pre-check=0');
	        self::addHeader('Pragma: no-cache');
            self::sendHeaders();
	        exit();
        }
    }


    /**
     * _preValidateRequest
     *
     * Basic client request validation
     *
     * @return null
     */

    private static function _preValidateRequest()
    {
        if (strlen($_SERVER['REQUEST_URI']) > 2048) {
            // long request, expected only maximum 2048 bytes length
            throw new SystemErrorException(array(
                'title'       => 'Request error',
                'description' => 'Request string too long'
            ));
        } else if (preg_match('/(?:%20|\/\/)+$/', $_SERVER['REQUEST_URI'])) {
            // double slash or bad spaces
            throw new SystemErrorException(array(
                'title'       => 'Request error',
                'description' => 'Broken query string format'
            ));
        }
    }
}
