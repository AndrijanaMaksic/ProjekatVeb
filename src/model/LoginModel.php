<?php

namespace vebProjekat\model;

use vebProjekat\core\Database;
class LoginModel{

    public function getRows( $email )
    {
        if ( Database::checkIfUserExists($email, 'user')) {
            return true;
        }
        return false;
    }
    public function checkUser( $email, $password )
    {
        $queryString = "SELECT * FROM user WHERE email = '".$email."' AND password = '".$password."'";
        $query = mysqli_query(Database::$connection = Database::connect(), $queryString);

        if ( $query != false )
        {
            if ( mysqli_num_rows($query) == 1 )
            {
                return true;
            }
        }
        return false;
    }


    public function findUserName( $email )
    {
        $queryString = "SELECT * FROM user WHERE email = '" . $email . "'";
        $query = mysqli_query(Database::$connection = Database::connect(), $queryString);
        if ($query != false)
        {
            if (mysqli_num_rows($query) == 1)
            {
                return mysqli_fetch_row($query)[1];
            }
        }
    }


}