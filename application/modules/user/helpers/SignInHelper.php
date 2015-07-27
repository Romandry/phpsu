<?php


/**
 * SignInHelper
 *
 * Readonly user sing in helper
 */

namespace modules\user\helpers;

class SignInHelper
{


    /**
     * trySignIn
     *
     * Try sign in process
     *
     * @param  StdClass $signInData Member sign in data
     * @return null
     */

    public static function trySignIn(\StdClass $signInData)
    {
        \View::addLanguageItem('SignInHelper');

        $memberData = \DBI::getConnection('master')->sendQuery(
            'SELECT cookie, password, status
                FROM members
                WHERE email = :email OR login = :login',
            array(
                ':email' => $signInData->login,
                ':login' => $signInData->login
            )
        )->fetch(\PDO::FETCH_OBJ);

        // member data not found
        if (!$memberData) {
            throw new \MemberErrorException(array(
                'title'      => \View::$language->sign_in_helper_error,
                'description'=> \View::$language->sign_in_helper_login_or_password_invalid
            ));
        }
        // compare password
        $hasSamePassword = \common\CryptHelper::verifyHash(
            $signInData->password,
            $memberData->password
        );
        if (!$hasSamePassword) {
            throw new \MemberErrorException(array(
                'title'      => \View::$language->sign_in_helper_error,
                'description'=> \View::$language->sign_in_helper_login_or_password_invalid
            ));
        }

        // sign in success
        $cnf = \App::getConfig('main')->system;
        $exp = time() + $cnf->cookie_expires_time;
        setcookie($cnf->cookie_name, $memberData->cookie, $exp, '/');
        throw new \MemberSuccessException(array(
            'title'       => \View::$language->sign_in_helper_success,
            'description' => \View::$language->sign_in_helper_success_description,
            'redirection' => '/'
        ));
    }
}
