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
        $iCount   = 1;
        $allPages = ceil($itemsTotal / $itemsPerPage);
        $items    = array();

        while ($iCount <= $allPages) {
            $url = $baseUrlPrefix . ($iCount > 1 ? '&page=' . $iCount : '');
            $items[] = (object) array(
                'url'     => $url,
                'number'  => $iCount,
                'current' => $iCount == $currentPage
            );
            $iCount += 1;
        }

        return $items;
    }
}
