<?php


/**
 * FsUtils
 *
 * Filesystem utilites
 */

class FsUtils
{


    /**
     * glob
     *
     * WARNING!
     * Origin PHP function glob() maybe returned FALSE value!
     * But we always expect array!
     *
     * @param string $pattern Filesystem path
     * @param int    $flags   PHP core GLOB_* constants
     * @return array          Founded items
     */

    public static function glob($pattern, $flags = 0)
    {
        if (!$result = glob($pattern, $flags)) {
            $result = array();
        }
        return $result;
    }


    /**
     * globRecursive
     *
     * Self recursively glob wrapper
     *
     * @param string $path Filesystem path
     * @param string $mask Find mask
     * @return array       Founded items
     */

    public static function globRecursive($path, $mask = "*")
    {

        $items = self::glob($path . $mask);
        $dirs = self::glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
        foreach ($dirs as $dir) {
            $items = array_merge(
                $items,
                self::globRecursive($dir . '/', $mask)
            );
        }
        return $items;

    }
}
