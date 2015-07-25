<?php


/**
 * CryptHelper
 *
 * This class provides a simple API
 * for secure password hashing and verification
 * with Blowfish hashing algorithm
 */

namespace common;

class CryptHelper
{


    /**
     * ITERATIONS_COUNT
     *
     * The base-2 logarithm of the iteration count for hashing,
     * this value must be in range 4-31
     */

    const ITERATIONS_COUNT = 13;


    /**
     * SALT_LENGTH
     *
     * Length of hashing salt,
     * but PHP sets a constant named CRYPT_SALT_LENGTH
     * which indicates the longest valid salt allowed for available hashes.
     * And here we can use builtin value
     */

    const SALT_LENGTH = CRYPT_SALT_LENGTH;


    /**
     * generateHash
     *
     * Generate a secure hash from input string with internal random salt
     *
     * @param  string $inputString   Input string for hashing
     * @param  int    $iterationsCnt Logarithm of iteration count for hashing
     * @return string                Hash of string, always 60 ASCII characters
     */

    public static function generateHash(
        $inputString,
        $iterationsCnt = self::ITERATIONS_COUNT
    )
    {
        self::_checkBlowFish();

        $hash = crypt($inputString, self::_generateSalt($iterationsCnt));
        if (mb_strlen($hash, '8bit') < 13) {
            throw new \SystemErrorException(array(
                'title'       => 'CryptHelper error',
                'description' => 'Internal error while generating hash'
            ));
        }

        return $hash;
    }


    /**
     * verifyHash
     *
     * Verify source string against a hash
     *
     * @param  string $inputString Input source string
     * @param  string $inputHash   Hash to verify of source string against
     * @return bool                Verify status
     */

    public static function verifyHash($inputString, $inputHash)
    {
        self::_checkBlowFish();

        $byHash = crypt($inputString, $inputHash);
        $length = mb_strlen($byHash, '8bit');
        if ($length !== mb_strlen($inputHash, '8bit')) {
            return false;
        }

        $same = 0;
        for ($i = 0; $i < $length; $i += 1) {
            $same |= (ord($byHash[$i]) ^ ord($inputHash[$i]));
        }

        return $same === 0;
    }


    /**
     * _generateSalt
     *
     * Generate new random salt for hashing
     *
     * @param  int    $iterationsCnt Logarithm of iteration count for hashing
     * @return string                Random salt string for hashing
     */

    private static function _generateSalt($iterationsCnt)
    {
        $l = self::SALT_LENGTH;
        $s = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $z = strlen($s) - 1;
        $o = '';
        while ($l--) {
            $o .= $s[ mt_rand(0, $z) ];
        }

        return sprintf('$2x$%02d$', $iterationsCnt) . $o;
    }


    /**
     * _checkBlowFish
     *
     * Check for availability of PHP crypt() with Blowfish hashing algorithm
     *
     * @return null
     */

    private static function _checkBlowFish()
    {
        if (!function_exists('crypt')) {
            throw new \SystemErrorException(array(
                'title'       => 'CryptHelper error',
                'description' => 'Requires the PHP crypt() function'
            ));
        } else if (!defined('CRYPT_BLOWFISH') || !CRYPT_BLOWFISH) {
            throw new \SystemErrorException(array(
                'title'       => 'CryptHelper error',
                'description' => 'Requires the Blowfish option of the PHP crypt() function'
            ));
        }
    }
}
