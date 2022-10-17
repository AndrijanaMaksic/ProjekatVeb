<?php

namespace vebProjekat\model;
use mPDF;
use vebProjekat\core\Database;

class UserModel
{

    public function getUser($email) {
        $db = Database::connect();

        $q = "SELECT 
            name, 
            surname, 
            email
        FROM user WHERE email='$email'";

        $query = mysqli_query($db, $q);
        return mysqli_fetch_all($query)[0];
    }


    public function getUserType($email) {
        $db = Database::connect();

        $q = "SELECT 
            user_type.type
        FROM user
        INNER JOIN user_type ON user.type_id = user_type.id
        WHERE user.email = $email;";
        $query = mysqli_query($db, $q);
        return mysqli_fetch_all($query)[0];
    }

    public function getAllUsers() {
        $db = Database::connect();

        $q = "SELECT * FROM user
        INNER JOIN user_type ON user.type_id = user_type.id
        WHERE user_type.type='customer';";
        $query = mysqli_query($db, $q);
        return mysqli_fetch_all($query);
    }

    public function deleteUser($username) {
        $db = Database::connect();
        $q = "DELETE FROM user WHERE username='$username';";
        $query = mysqli_query($db, $q);
    }

    public function addInformations(string $email, string $name, string $surname) {
        $db = Database::connect();

        $q = "UPDATE user SET 
        name = '$name',
        surname = '$surname' 
        WHERE email = '$email';";

        echo $q;
        $query = mysqli_query($db, $q);
    }


    public function addPerson($name, $surname, $username, $password, $email) {
        $db = Database::connect();
        $q = "INSERT INTO user(name, surname, username, password, email, type_id) VALUES (
            '$name', '$surname', '$username', '$password', '$email', 2
            )";
        $query = mysqli_query($db, $q);
    }

}