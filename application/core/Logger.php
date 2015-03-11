<?php


/**
 * Logger
 *
 * Rotate log files class
 */

class Logger
{


    /**
     * writeItem
     *
     * Write into log file one report line
     *
     * @param  array $item Loggable report data
     * @return null
     */

    public static function writeItem(array $item)
    {
        $existsLog = false;
        $logDir    = APPLICATION . 'logs';
        $logFile   = $logDir . '/main.log';

        if (!is_dir($logDir)) {
            exit('Log path ' . $logDir . ' is not directory or not exists!');
        } else if (!is_writable($logDir)) {
            exit('Log path ' . $logDir . ' don\'t have writable permissions!');
        }

        if (is_file($logFile)) {
            if (!is_writable($logFile)) {
                exit('Log file ' . $logFile . ' don\'t have writable permission!');
            }
            $existsLog  = true;
            $maxLogSize = App::getConfig('main')->system->log_file_max_size;
            if (filesize($logFile) > $maxLogSize) {
                $logName = date('Y-m-d_H.i.s');
                $archLog = $logDir . '/main_' . $logName . '.log';
                rename($logFile, $archLog);
                $existsLog = false;
            }
        }

        $item['url'] = Request::getRawUrl();

        $item = ($existsLog ? ",\n" : '') . json_encode($item);
        file_put_contents($logFile, $item, LOCK_EX | FILE_APPEND);
        if (!$existsLog) {
            chmod($logFile, 0666);
        }
    }
}
