<?php

namespace vebProjekat\model;

use vebProjekat\core\Database;

class RegisterModel
{

    public function registerUser($email, $pass, $username) {
        $d = Database::connect();

        $q = "INSERT INTO user(email, password, username, type_id) VALUES (
            '$email', '$pass', '$username', 2)";
        echo $q;
        $query = mysqli_query($d, $q);
        if ( $query != NULL ) {
            return true;
        }
        return false;
    }

    public function usernameExists( $username ) {
        $queryString = "SELECT * FROM user WHERE username = '" . $username . "'";

        $d = Database::connect();
        $query = mysqli_query($d, $queryString);
        if (mysqli_num_rows($query) == 0)
        {
            return false;
        }

        return true;
    }

    public function emailExists( $email ) {

        $d = Database::connect();

        $queryString = "SELECT * FROM user WHERE email = '" . $email . "'";
        $query = mysqli_query($d, $queryString);
        if (mysqli_num_rows($query) == 0)
        {
            return false;
        }

        return true;
    }

    public function findUser( $email )
    {
        $d = Database::connect();
        $queryString = "SELECT * FROM user WHERE email = '" . $email . "'";
        $query = mysqli_query($d, $queryString);

        if ($query != false)
        {
            if (mysqli_num_rows($query) == 1)
            {
                return mysqli_fetch_row($query)[0];
            }
        }
    }
}