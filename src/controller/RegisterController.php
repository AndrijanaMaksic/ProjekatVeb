<?php

namespace vebProjekat\controller;

use vebProjekat\model\RegisterModel;

class RegisterController
{
    public RegisterModel $registerModel;
    public function __construct()
    {
        $this->registerModel = new RegisterModel();
    }

    public function register()
    {

        $email = $_POST['email'];
        $pass = $_POST['pass'];

        $username = $_POST['username'];
        if ( !$this->registerModel->emailExists($email) ) {
            if ( $this->registerModel->usernameExists($username) == false) {
                if ( $this->registerModel->registerUser($email, $pass, $username))
                {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION["loggedStatus"] = true;
                    $_SESSION["userName"] = $name;
                    $_SESSION["email"] = $email;
                    header("LOCATION: /");
                }
            }
            else {
                $this->render('register', [ "message" => "Korisničko ime je zauzeto" ] );
            }
        } else {
            $this->render('register', [ "message" => "Mejl se vec koristi" ] );
        }

    }
}