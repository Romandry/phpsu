<?php


/**
 * SystemException
 *
 * System exception type
 */

class SystemException extends Exception
{


    /**
     * $_type
     *
     * Type of exception
     */

    protected $_type = 'system';


    /**
     * $_report
     *
     * Exception report
     */

    protected $_report = array();


    /**
     * __construct
     *
     * Create exception with report data
     *
     * @param  array $report Report data
     * @return null
     */

    public function __construct(array $report)
    {
        $this->_report = $report;
        if (!array_key_exists('code', $this->_report)) {
            $this->_report['code'] = 0;
        }

        $this->_report['initiator_id'] = Member::getProfile()->id;

        $this->_report['type']  = $this->_type;
        $this->_report['file']  = $this->file;
        $this->_report['line']  = $this->line;
        $this->_report['trace'] = parent::getTrace();

        if (App::getConfig('main')->system->write_log) {
            Logger::writeItem($this->_report);
        }
    }


    /**
     * getReport
     *
     * Return report data
     *
     * @return array Report data
     */

    public function getReport()
    {
        return $this->_report;
    }
}
