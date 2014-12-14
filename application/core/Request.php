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
     * $_client
     *
     * Request client information
     */

    private static $_client = array();


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
        self::_storeClientInfo();


        /**
         * I don't like the combination of "/?" in the URL,
         * and I'm want after the "action" immediately "?"
         */

        self::$_rawUrl = preg_replace('/([^\/=\?&]+)\/(\?)/', '$1$2', $_SERVER['REQUEST_URI']);
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
     * Return all available client info
     *
     * @return array Available client info
     */

    public static function getClientInfo()
    {
        return self::$_client;
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
        return array_key_exists($key, self::$_params) ? self::$_params[$key] : $default;
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
        return (isset($_POST) && sizeof($_POST) > 0);
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

        if (array_key_exists('HTTP_X_MOZ', $_SERVER) && $_SERVER['HTTP_X_MOZ'] == 'prefetch') {
            self::$_headers = array();
	        self::addHeader('HTTP/1.1 403 Prefetching Forbidden');
	        self::addHeader('Expires: Thu, 21 Jul 1977 07:30:00 GMT');
	        self::addHeader('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
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
     * TODO maybe this need
     * Content-Length bug fix on php 5.4
     * see more information:
     * http://www.exploit-db.com/exploits/18665/
     *
     * @return null
     */

    private static function _preValidateRequest()
    {

        if (strlen($_SERVER['REQUEST_URI']) > 2048) {
            // long request, expected only maximum 2048 bytes length
            $_SERVER['REQUEST_URI'] = '';
            throw new SystemErrorException(array(
                'title'       => 'Request error',
                'description' => 'Request string too long'
            ));
        } else if (strstr($_SERVER['REQUEST_URI'], '//')) {
            // double slash
            throw new SystemErrorException(array(
                'title'       => 'Request error',
                'description' => 'Double slash found on request URI'
            ));
        } else if (preg_match('/(?:%20)+$/', $_SERVER['REQUEST_URI'])) {
            // bad spaces
            throw new SystemErrorException(array(
                'title'       => 'Request error',
                'description' => 'Bad SEO spaces on request URI'
            ));
        }

    }


    /**
     * _storeClientInfo
     *
     * Store client information
     * IP, useragent, referer, etc.
     *
     * @return null
     */

    private static function _storeClientInfo()
    {

        $expectedKeys = array(
            'HTTP_USER_AGENT',
            'HTTP_REFERER',
            'HTTP_ACCEPT',
            'HTTP_ACCEPT_LANGUAGE',
            'HTTP_ACCEPT_ENCODING'
        );

        foreach ($expectedKeys as $v) {
            $lcv = strtolower($v);
            self::$_client[$lcv] = array_key_exists($v, $_SERVER) ? $_SERVER[$v] : '[no match]';
        }

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
        self::$_client['ip'] = (!$ip || $ip == 'unknown') ? $radd : $ip;

    }
}
