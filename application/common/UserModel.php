<?php


/**
 * UserModel
 *
 * User representation model
 */

namespace common;

class UserModel
{


    /**
     * isExists
     *
     * Check for exists (registered) data (value)
     *
     * @param  string $field Checked field
     * @param  mixed  $data  Checked data (value)
     * @return bool          Status of existence
     */

    public function isExists($field, $data)
    {
        $conn = \DBI::getConnection('master');
        $stmt = $conn->sendQuery(
            'SELECT (1) status
                FROM members
                WHERE ' . $field . ' = :' . $field,
            array(':' . $field => $data)
        );

        return !!$stmt->fetch(\PDO::FETCH_COLUMN);
    }


    /**
     * createUser
     *
     * Create a new user
     *
     * @param  StdClass $userData User data
     * @return int                User identification number
     */

    public function createUser(\StdClass $userData)
    {
        $conn = \DBI::getConnection('master');

        $conn->sendQuery(
            "INSERT INTO members (
                    group_id,
                    cookie,
                    email,
                    login,
                    password,
                    time_zone,
                    creation_date,
                    last_ip,
                    last_visit,
                    status,
                    activation_hash,
                    avatar
                ) VALUES (
                    :group_id,
                    :cookie,
                    :email,
                    :login,
                    :password,
                    :time_zone,
                    NOW(),
                    '',
                    NOW(),
                    :status,
                    :activation_hash,
                    :avatar
                )",
            array(
                ':group_id'        => $userData->group_id,
                ':cookie'          => $userData->cookie,
                ':email'           => $userData->email,
                ':login'           => $userData->login,
                ':password'        => $userData->password,
                ':time_zone'       => $userData->time_zone,
                ':status'          => $userData->status,
                ':activation_hash' => $userData->activation_hash,
                ':avatar'          => $userData->avatar
            )
        );

        return $conn->lastInsertId();
    }
}
