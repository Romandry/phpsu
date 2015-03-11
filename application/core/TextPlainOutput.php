<?php


/**
 * TextPlainOutput
 *
 * Text plain output context builder class
 */

class TextPlainOutput
{


    /**
     * getContent
     *
     * Return generated plain text output string
     *
     * @param  mixed  $data    Input data
     * @param  int    $lastPad Last left padding size
     * @return string          Generated output string
     */

    public static function getContent($data, $lastPad = 0)
    {
        if (!is_array($data)) {
            $data = array($data);
        }

        $output = '';
        $currentPad = self::_getPadSize(array_keys($data));

        foreach ($data as $k => $v) {
            if (is_object($v)) {
                $v = (array) $v;
            }
            $k = is_numeric($k) ? '' : ($k . ': ');
            $output .= PHP_EOL . str_repeat(' ', $lastPad);
            $output .= str_pad($k, $currentPad, ' ', STR_PAD_RIGHT);
            if (is_array($v)) {
                $output .= self::getContent($v, $currentPad);
            } else {
                $output .= $v;
            }
        }

        return $output . PHP_EOL;
    }


    /**
     * _getPadSize
     *
     * Return max left padding size of keys collection
     *
     * @param  array $names Keys collection
     * @return int          Max left padding size
     */

    private static function _getPadSize($names)
    {
        $len = array();
        foreach ($names as $name) {
            $len[] = mb_strlen($name);
        }

        return ($len ? max($len) : 0) + 2;
    }
}
