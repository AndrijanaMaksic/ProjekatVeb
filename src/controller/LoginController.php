<?php

namespace vebProjekat\controller;
USE vebProjekat\core\Application;
USE vebProjekat\core\Controller;
USE vebProjekat\model\LoginModel;

class LoginController extends Controller
{
    public LoginModel $loginModel;
    public function __construct() {
        $this->loginModel = new LoginModel();
    }

    public function checkUser() {


        $email = $_POST['email'];
        $pass = $_POST['pass'];

        if ( $this->loginModel->getRows($email))
        {

            if ( $this->loginModel->checkUser($email, $pass) )
            {

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION["loggedStatus"] = true;
                //$_SESSION["userName"] = $this->loginModel->findUserName( $email );
                $_SESSION['email'] = $email;

                header("LOCATION: /");
            }
            else
            {
                $this->render('login', [ "message" => "Šifra nije tačna"]);
            }
        }
        else {
            $this->render('login', [ "message" => "Mejl ili šifra nije tačna"]);
        }
    }


    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION["loggedStatus"] = false;
        $_SESSION["userName"] = "";
        $_SESSION["email"] = "";
        header("LOCATION: /");
    }
}