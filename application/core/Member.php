<?php


class Member
{


    /**
     * $_storageKey
     *
     * Member working cache storage key
     */

    private static $_storageKey = '__member_cache';


    /**
     * $_profile
     *
     * Default member profile
     */

    private static $_profile = null;


    /**
     * $_permissions
     *
     * Default member permissions
     * Empty array denied from all delegated actions
     */

    private static $_permissions = array();


    /**
     * getProfile
     *
     * Return full profile data of member
     *
     * @return StdClass Profile data
     */

    public static function getProfile()
    {
        return self::$_profile;
    }


    /**
     * getPermissions
     *
     * Return existst permissions
     *
     * @return StdClass Profile permissions
     */

    public static function getPermissions()
    {
        return self::$_permissions;
    }


    /**
     * isPermission
     *
     * Return status of existst permission
     *
     * @return bool Status of existst permission
     */

    public static function isPermission($name)
    {
        return property_exists(self::$_permissions, $name);
    }


    /**
     * isAuth
     *
     * Return auth status of member
     *
     * @return bool Auth status of member
     */

    public static function isAuth()
    {
        return self::$_profile->auth;
    }


    /**
     * init
     *
     * Return auth status of member
     *
     * @return bool Auth status of member
     */

    public static function initGuest()
    {
        self::$_profile = App::getConfig('member_defaults');
    }
}
