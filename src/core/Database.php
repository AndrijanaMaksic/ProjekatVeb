<?php

namespace vebProjekat\core;

class Database
{
    protected static string $dbName = 'jewelry';
    protected static string $dbUser = 'root';
    protected static string $dbPass = '';
    protected static string $dbServer = 'localhost';
    public static $connection;
    public static function connect() {
        return mysqli_connect(self::$dbServer, self::$dbUser, self::$dbPass, self::$dbName);
    }

    public static function checkIfUserExists( $user, $table ): bool
    {
        self::$connection = self::connect();

        $queryString = "SELECT * FROM $table WHERE email = '".$user."'";
        $query = mysqli_query(self::$connection, $queryString);


        if (!$query) { return false; }
        if ( mysqli_num_rows($query) != 0 ) {
            return true;
        }
        return false;
    }

}