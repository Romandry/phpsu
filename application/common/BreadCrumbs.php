<?php


/**
 * BreadCrumbs
 *
 * Store and view site breadcrums
 */

namespace common;

class BreadCrumbs
{


    /**
     * $_items
     *
     * Breadcrumbs stack items data
     */

    private static $_items = array();


    /**
     * getItems
     *
     * Will return array of items (current stack state)
     *
     * @return array Breadcrumbs items
     */

    public static function getItems()
    {
        return self::$_items;
    }


    /**
     * getStackSize
     *
     * Will return current size of stack (number of items)
     *
     * @return int Number of items
     */

    public static function getStackSize()
    {
        return sizeof(self::$_items);
    }


    /**
     * clearStack
     *
     * Clear current stack state to empty array
     *
     * @return null
     */

    public static function clearStack()
    {
        self::$_items = array();
    }


    /**
     * appendItem
     *
     * Append new breadcrumbs stack item
     *
     * @param  BreadCrumbsItem Breadcrumbs stack item
     * @return null
     */

    public static function appendItem(BreadCrumbsItem $item)
    {
        if (!self::getStackSize()) {
            \View::addLanguageItem('BreadCrumbs');
            self::$_items[] = new BreadCrumbsItem(
                '/',
                \View::$language->breadcrumbs_home_name
            );
        }
        self::$_items[] = $item;
    }


    /**
     * appendItems
     *
     * Append many breadcrumbs stack items
     *
     * @param  array Breadcrumbs stack items data
     * @return null
     */

    public static function appendItems(array $items)
    {
        foreach ($items as $item) {
            self::appendItem($item);
        }
    }
}
