<?php


/**
 * HashHelper
 *
 * Some functions for different hash or serial values generation
 */

namespace common;

class HashHelper
{


    /**
     * getRandomPassword
     *
     * Generate new random base-62 string (0-9A-Za-z) password
     *
     * @param  int    $length Length of generated password
     * @return string         Random string of password
     */

    public static function getRandomPassword($length = 8)
    {
        $password = self::_baseConvert(md5(mt_rand() . microtime()), 16, 62);
        return substr($password, 0, $length);
    }


    /**
     * getUniqueKey
     *
     * Generate unique base-62 string (0-9A-Za-z)
     *
     * @return string Unique key
     */

    public static function getUniqueKey()
    {
        $base16Key = uniqid(mt_rand(), false) . uniqid(mt_rand(), false);
        return self::_baseConvert($base16Key, 16, 62);
    }


    /**
     * getSerialKey
     *
     * Generate serial base-62 string (0-9A-Za-z)
     *
     * @return string Unique serial key
     */

    public static function getSerialKey()
    {
        $stamp = preg_replace('/^0\.(\d{8})\s(\d+)$/', '$2$1', microtime());
        return self::_baseConvert($stamp, 10, 62);
    }


    /**
     * getSerialDirPath
     *
     * Generate serial base-62 relative subdirectory path (0-9A-Za-z)
     *
     * @return string Serial path of subdirectory
     */

    public static function getSerialDirPath()
    {
        $dirPath = array();
        foreach (array('Y', 'm', 'd', 'H', 'i') as $part) {
            $dirPath[] = self::_baseConvert(date($part), 10, 62);
        }

        return join('/', $dirPath);
    }


    /**
     * _baseConvert
     *
     * Convert values from base-A to base-B with GMP-functions
     *
     * @param  string $value Input value for convert
     * @param  int    $from  From base-A value
     * @param  int    $to    To base-B value
     * @return string        Result string
     */

    private static function _baseConvert($value, $from, $to)
    {
        if (!function_exists('gmp_init')) {
            throw new \SystemErrorException(array(
                'title'       => 'HashHelper error',
                'description' => 'Requires the PHP gmp module'
            ));
        }

        return gmp_strval(gmp_init($value, $from), $to);
    }
}
