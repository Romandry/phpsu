<?php


class Member
{


    /**
     * $_isAuth
     *
     * Auth status
     */

    private static $_isAuth = false;


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

    private static $_permissions = null;


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
     * hasPermission
     *
     * Return status of existst permission
     *
     * @return bool Status of existst permission
     */

    public static function hasPermission($name)
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
        return self::$_isAuth;
    }


    /**
     * beforeInit
     *
     * Init default profile via config
     *
     * @return null
     */

    public static function beforeInit()
    {
        $isCLI = App::isCLI();
        $prf = App::getConfig($isCLI ? 'member-cli' : 'member-guest');
        $prf->avatar = '//' . App::getConfig('hosts')->st . $prf->avatar;

        self::$_permissions = new StdClass();
        self::$_profile = $prf;
        if ($isCLI) {
            self::$_isAuth = true;
        }
    }


    /**
     * init
     *
     * Try member authenfication
     *
     * @return null
     */

    public static function init()
    {
        $needPermissions = false;
        if (App::isCLI()) {
            $needPermissions = true;
        } else {
            $cnf = App::getConfig('main')->system;
            if (array_key_exists($cnf->cookie_name, $_COOKIE)) {
                $value = (string) $_COOKIE[$cnf->cookie_name];
                $value = trim(substr($value, 0, 255));
                if ($value) {

                    $conn = DBI::getConnection('slave');
                    $data = $conn->sendQuery(
                        'SELECT
                                m.*,
                                g.priority group_priority,
                                g.is_protected
                            FROM members m
                            LEFT JOIN groups g
                                ON g.id = m.group_id
                            WHERE m.cookie = :cookie',
                        array(':cookie' => $value)
                    )->fetch(PDO::FETCH_OBJ);

                    if (!$data) {
                        self::signOut();
                    } else {

                        $needPermissions = true;
                        $expires = time() + $cnf->cookie_expires_time;
                        setcookie($cnf->cookie_name, $value, $expires, '/');

                        if (!$data->avatar) {
                            $data->avatar = self::$_profile->avatar;
                        }
                        self::$_isAuth  = true;
                        self::$_profile = $data;

                    }

                }
            }
        }

        self::_setEnvironment();
        if ($needPermissions) {
            self::_initPermissions();
        }
    }


    /**
     * signOut
     *
     * Sign out with remove member auth cookie
     *
     * @return null
     */

    public static function signOut()
    {
        setcookie(App::getConfig('main')->system->cookie_name, '', -1, '/');
    }


    /**
     * _setEnvironment
     *
     * Set member environment (time_zone, language, etc)
     *
     * @return null
     */

    private static function _setEnvironment()
    {
        $conn = DBI::getConnection('slave');
        // set timezone
        $tz = self::$_profile->time_zone
            ? self::$_profile->time_zone
            : App::getConfig('main')->site->default_time_zone;
        $conn->sendQuery(
            'SET time_zone = :time_zone',
            array(':time_zone' => $tz)
        );
    }


    /**
     * _initPermissions
     *
     * Set member role permissions with group_id
     *
     * @return null
     */

    private static function _initPermissions()
    {
        $conn = DBI::getConnection('slave');
        $permissions = $conn->sendQuery(
            'SELECT
                    p.name
                FROM groups_permissions gp
                INNER JOIN permissions p
                    ON p.id = gp.permission_id
                WHERE gp.group_id = :group_id',
            array(':group_id' => self::$_profile->group_id)
        )->fetch(PDO::FETCH_OBJ);
        if ($permissions) {
            self::$_permissions = $permissions;
        }
    }
}
