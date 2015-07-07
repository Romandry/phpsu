<?php


/**
 * PaginationHelper
 *
 * Pagination helper implementation
 */

namespace common;

class PaginationHelper
{


    /**
     * getItems
     *
     * Will return pagination items
     *
     * @param  string $baseUrlPrefix Base URL prefix
     * @param  int    $currentPage   Number of current page
     * @param  int    $itemsPerPage  Number of items per page
     * @param  int    $itemsTotal    Total items number
     * @return array                 Pagination items
     */

    public static function getItems(
        $baseUrlPrefix,
        $currentPage,
        $itemsPerPage,
        $itemsTotal
    )
    {
        $parts    = explode('?', $baseUrlPrefix);
        $iCount   = 1;
        $allPages = ceil($itemsTotal / $itemsPerPage);
        $items    = array();

        if (sizeof($parts) < 2) {
            $parts[1] = '';
        }
        parse_str($parts[1], $params);

        while ($iCount <= $allPages) {
            $par = array();
            if ($iCount > 1) {
                $par['page'] = $iCount;
            }
            $url = http_build_query(array_merge($params, $par));
            $items[] = (object) array(
                'url'     => $parts[0] . ($url ? '?' . $url : ''),
                'number'  => $iCount,
                'current' => $iCount == $currentPage
            );
            $iCount += 1;
        }

        return $items;
    }
}
