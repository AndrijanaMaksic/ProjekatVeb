<?php

namespace vebProjekat\controller;

use vebProjekat\core\Application;
use vebProjekat\core\Controller;
use vebProjekat\model\UserModel;

class UserController extends Controller
{
    public UserModel  $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function getUsers() {
        $nav = "";
        $nav = $this->render('/layout', []);

        if(empty($_GET)) {
            $_GET['page1'] = true;
        }
        $array = $this->userModel->getAllUsers();

        $numOfPages = ceil(count($array) / 6);
        $n = file_get_contents(Application::$ROOT_DIR . "/vebProjekat/src/view/users.twig");

        $pages = "<ul class='pagination'>";
        for($i = 1; $i <= $numOfPages; $i++) {
            $pages = $pages . "<li class='page-item'><a class='page-link' href='/korisnici?page$i=true'>$i</a></li>";
        }

        $pages = $pages . "</ul>";
        $n = str_replace('{{ pages }}', $pages, $n);

        $items = "";
        for($i = 1; $i <= $numOfPages; $i++) {
            if (isset($_GET['page'.$i])) {
                foreach((array_slice($array, ($i-1) * 6, 6)) as $customer) {
                    $item = file_get_contents(Application::$ROOT_DIR . "/vebProjekat/src/view/user.twig");
                    $item = str_replace('{{ ime }}', $customer[3], $item);
                    $item = str_replace('{{ prezime }}', $customer[4], $item);
                    $item = str_replace('{{ email }}', $customer[1], $item);
                    $item = str_replace('{{ username }}', $customer[5], $item);
                    $item = str_replace('{{ username1 }}', $customer[5], $item);

                    $items = $items . $item;
                }
                $n = str_replace('{{content}}', $items, $n);
            }
        }
        $uri = $_SERVER['REQUEST_URI'];
        $username1 = trim($uri, '=true');
        $username1 = substr($username1, 13);
        echo $username1;
        var_dump($username1);
        if(isset($_GET['ukloni'.$username1])) {
            $this->userModel->deleteUser($username1);
            header("Location:/users");
        }
        $nav = $nav . $n;
        return $nav;
    }
    public function updateUser() {
        $content = file_get_contents(Application::$ROOT_DIR . "/vebProjekat/src/view/updateProfile.twig");
        $user = $this->getUseremail();
        $userInfo = $this->userModel->getUser($user);

        if(!empty($_POST)) {

            $name = $_POST['ime'];
            $surname = $_POST['prezime'];

            $this->userModel->addInformations($user, $name, $surname);
            $_POST['ime'] = "";
            $_POST['prezime'] = "";
            header("Location: /updateProfile");
        } else {
            if($userInfo[0] != NULL) {
                $content = str_replace('{{ ime }}', $userInfo[0], $content);
            } else {
                $content = str_replace('{{ ime }}', "", $content);
            }
            if($userInfo[1] != NULL) {
                $content = str_replace('{{ prezime }}', $userInfo[1], $content);
            }  else {
                $content = str_replace('{{ prezime }}', "", $content);
            }
        }
        $nav = $this->render('/layout', []);

        $nav = $nav . $content;

        return $nav;
    }


    public function updateInformations(string $email, string $name, string $surname) {
        $this->userModel->addInformations($email, $name, $surname);
    }

    public function addPerson() {
        $content = file_get_contents(Application::$ROOT_DIR . "/vebProjekat/src/view/addPerson.twig");
        if(!empty($_POST)) {
            $name = $_POST['ime'];
            $surname = $_POST['prezime'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];

            $this->userModel->addPerson($name, $surname, $username, $password, $email);
            $_POST['ime'] = "";
            $_POST['prezime'] = "";
            $_POST['username'] = "";
            $_POST['password'] = "";
            $_POST['email'] = "";
            header("Location: /users");
        }
        $nav = $this->render('/layout', []);

        $nav = $nav . $content;

        return $nav;
    }

    public function listOfUsers() {
        $this->userModel->report();
    }
}