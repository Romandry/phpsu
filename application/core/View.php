<?php


/**
 * View
 *
 * Global view class
 */

class View
{


    /**
     * $_availableContexts
     *
     * All available output contexts
     */

    private static $_availableContexts = array('html', 'json', 'xml', 'txt');


    /**
     * $_outputContext
     *
     * Current output context type
     */

    private static $_outputContext = null;


    /**
     * $_lockedContext
     *
     * Lock output context status
     */

    private static $_lockedContext = false;


    /**
     * $_defaultXSDSchema
     *
     * Default structure of XSD-schema for XML generation
     */

    private static $_defaultXSDSchema = array('name' => 'response');


    /**
     * $_XSDSchema
     *
     * Current XSD-schema for XML generation
     */

    private static $_XSDSchema = array('name' => 'response');


    /**
     * $_docType
     *
     * XML doctype, now supported only SYSTEM
     */

    private static $_docType = null;


    /**
     * $_layout
     *
     * Output layout
     */

    private static $_layout = null;


    /**
     * $_protectedData
     *
     * Protected data for output layout
     */

    private static $_protectedData = array();


    /**
     * $_data
     *
     * Public data for output
     */

    private static $_data = array();


    /**
     * $_currentLang
     *
     * Current value of language name
     */

    private static $_currentLang = null;


    /**
     * $_langItems
     *
     * Loaded language items
     */

    private static $_langItems = array();


    /**
     * $_langStorageKey
     *
     * Language storage key
     */

    private static $_langStorageKey = '__view_languge_key';


    /**
     * $language
     *
     * Language phrases object
     */

    public static $language = array();


    /**
     * init
     *
     * View intialization
     *
     * @return null
     */

    public static function init()
    {

        $cnf = App::getConfig('main');
        self::setOutputContext($cnf->system->default_output_context);
        if (!$lang = Storage::read(self::$_langStorageKey)) {
            $lang = $cnf->site->default_language;
        }
        self::setLanguage($lang);

    }


    /**
     * setOutputContext
     *
     * Set output context type
     *
     * @param  string $type Type of output context
     * @return null
     */

    public static function setOutputContext($type)
    {

        if (!in_array($type, self::$_availableContexts)) {
            throw new SystemErrorException(array(
                'title'       => 'View error',
                'description' => 'Unavailable output context: ' . $type
            ));
        }
        if (!self::$_lockedContext) {
            self::$_outputContext = $type;
        }

    }


    /**
     * lockOutputContext
     *
     * Disable feature of change output context
     *
     * @return null
     */

    public static function lockOutputContext()
    {
        self::$_lockedContext = true;
    }


    /**
     * unlockOutputContext
     *
     * Enable feature of change output context
     *
     * @return null
     */

    public static function unlockOutputContext()
    {
        self::$_lockedContext = false;
    }


    /**
     * setLanguage
     *
     * Loading language environment
     *
     * @param  string $name Name of language
     * @return null
     */

    public static function setLanguage($name)
    {

        if ($name != self::$_currentLang) {

            $langDir = APPLICATION . 'languages/' . $name;
            self::$language = array();
            foreach (self::$_langItems as $langFile) {
                $langPath = $langDir . '/' . $langFile . '.php';
                self::$language = array_merge(self::$language, (require $langPath));
            }

            self::$_currentLang = $name;
            storage::write(self::$_langStorageKey, $name);
            self::$language = (object) self::$language;

        }

    }


    /**
     * getLanguage
     *
     * Return current language name
     *
     * @return string Name of language
     */

    public static function getLanguage()
    {
        return self::$_currentLang;
    }


    /**
     * addLanguageItem
     *
     * Loading new language environment item
     *
     * @param  string $name Name of language item
     * @return null
     */

    public static function addLanguageItem($name)
    {

        if (self::$_currentLang && !in_array($name, self::$_langItems)) {
            $lang = APPLICATION . 'languages/' . self::$_currentLang . '/' . $name . '.php';
            self::$_langItems[] = $name;
            self::$language = (object) array_merge((array) self::$language, (require $lang));
        }

    }


    /**
     * setLayout
     *
     * Set output layout filename
     *
     * @param  string $layoutName Name of layout file
     * @return null
     */

    public static function setLayout($layoutName)
    {
        self::$_layout = $layoutName;
    }


    /**
     * setXSDSchema
     *
     * Set XDS-schema array structure for XML output context
     *
     * @param  array $schema XSD schema array
     * @return null
     */

    public static function setXSDSchema($schema)
    {
        XmlSchemaValidator::check($schema);
        self::$_XSDSchema = $schema;
    }


    /**
     * isAssigned
     *
     * Return status of assigned public output variable
     *
     * @param  string $key Key of public variable
     * @return bool        Status of assigned variable
     */

    public static function isAssigned($key)
    {
        return array_key_exists($key, self::$_data);
    }


    /**
     * assignProtected
     *
     * Asssign protected data into output
     *
     * @param  mixed $item Associative array or string key of next argument
     * @param  mixed $i    Assigned data or null
     * @return null
     */

    public static function assignProtected($item, $i = null)
    {
        self::_assignData($item, $i);
    }


    /**
     * assign
     *
     * Asssign public data into output
     *
     * @param  mixed $item Associative array or string key of next argument
     * @param  mixed $i    Assigned data or null
     * @return null
     */

    public static function assign($item, $i = null)
    {
        self::_assignData($item, $i, true);
    }


    /**
     * assignException
     *
     * Assign exception object report data into output,
     * maybe choose layout to exception
     *
     * @param  Exception $e Exception object
     * @return null
     */

    public static function assignException($e)
    {

        $isDebug = App::getConfig('main')->system->debug_mode;
        if (!($e instanceof SystemException)) {
            UnexpectedException::take($e, $isDebug);
        } else {

            $report = $e->getReport();
            self::$_data = array();

            if ($isDebug) {
                self::$_layout = 'debug.phtml';
                self::assign('report', $report);
            } else {

                /*self::$layout = 'exception.phtml';
                if ($e instanceof SystemErrorException) {
                    $report['code']        = 404;
                    $report['title']       = view::$language->error . ' 404';
                    $report['description'] = view::$language->page_not_found;
                }

                if (self::$outputContext == 'json') {
                    self::assign('report', $report);
                } else {

                    self::assign($report);
                    if ($report['code'] == 404) {
                        request::addHeader($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
                    }
                    if (!self::isAssigned('hosts')) {
                        self::assign('hosts', app::getConfig('hosts.json'));
                    }

                }*/

            }

        }

    }


    /**
     * draw
     *
     * Try draw output layout
     *
     * @return null
     */

    public static function draw()
    {

        $renderTries = 2;
        while ( $renderTries-- ) {

            try {


                ob_start();
                // txt context
                if (self::$_outputContext == 'txt') {
                    Request::addHeader('Content-Type: text/plain');
                    $raw = TextPlainOutput::getContent(self::$_data);
                // json context
                } else if (self::$_outputContext == 'json') {
                    Request::addHeader('Content-Type: application/json');
                    $raw = json_encode(self::$_data ? self::$_data : new StdClass());
                // xml context
                } else if (self::$_outputContext == 'xml') {
                    Request::addHeader('Content-Type: application/xml');
                    $raw = XmlOutput::getContent(self::$_data, self::$_XSDSchema, self::$_docType);
                // html context error
                } else if (!self::$_layout) {
                    throw new SystemErrorException(array(
                        'title'       => 'View error',
                        'description' => 'Layout is not set'
                    ));
                // html context
                } else {
                    Request::addHeader('Content-Type: text/html; charset=utf-8');
                    self::_normalizeHtmlContext();
                    extract(self::$_protectedData);
                    extract(self::$_data);
                }

                // set non-html context raw layout
                if (self::$_outputContext != 'html') {
                    self::$_layout = 'raw.phtml';
                }
                require self::$_layout;


            } catch (Exception $e) {
                ob_clean();
                self::assignException($e);
                continue;
            }
            $layoutContent = ob_get_clean();
            break;

        }

        if (!isset($layoutContent) && isset($e)) {
            UnexpectedException::take($e, true);
        }
        Request::sendHeaders();
        echo $layoutContent;

    }


    /**
     * _assignData
     *
     * Assign data into output
     *
     * @param  mixed $item     Associative array or string key of next argument
     * @param  mixed $i        Assigned data or null
     * @param  bool  $toPublic Assign type flag (public or protected data)
     * @return null
     */

    private static function _assignData($item, $i = null, $toPublic = false)
    {

        $data = array();
        if (!is_array($item) and $i !== null) {
            $data[$item] = $i;
        } else if (is_array($item) and $i === null) {
            $data = $item;
        } else if (is_object($item) and $i === null) {
            $data = array($item);
        } else {
            throw new SystemErrorException(array(
                'title'       => 'View error',
                'description' => 'Assign method expects: view::assign(\'key\', $data);'
            ));
        }

        if ($toPublic) {
            self::$_data = array_merge(self::$_data, $data);
        } else {
            self::$_protectedData = array_merge(self::$_protectedData, $data);
        }

    }


    /**
     * _normalizeHtmlContext
     *
     * Normalize data of html output context type
     *
     * @return null
     */

    private static function _normalizeHtmlContext()
    {

        $config = App::getConfig('main')->site;
        if (!array_key_exists('meta_description', self::$_data)) {
            self::$_data['meta_description'] = $config->default_description;
        }
        if (!array_key_exists('meta_keywords', self::$_data)) {
            self::$_data['meta_keywords'] = $config->default_keywords;
        }
        if (!array_key_exists('title', self::$_data)) {
            self::$_data['title'] = $config->default_title;
        }

    }
}
