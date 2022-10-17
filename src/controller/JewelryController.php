<?php

namespace vebProjekat\controller;

use vebProjekat\core\Application;
use vebProjekat\core\Controller;
use vebProjekat\model\JewelryModel;

class JewelryController extends Controller
{
    public JewelryModel $jewelryModel;

    public function __construct(){
        $this->jewelryModel = new JewelryModel();
    }
    public function getJewelryList(){
        $preview = file_get_contents(Application::$ROOT_DIR . "/vebProjekat/src/view/jewelry.twig");
        $user = $this->getUseremail();
        $userType = $this->getUsertype($user);
        $jewelryList = $this->jewelryModel->findAll();

        $n = "";
        $nav = $this->render('/layout', []);
        if(empty($_GET)) {
            $_GET['page1'] = true;
        }

        if(!empty($_POST) && isset($_POST['search'])) {
            $search = $_POST['search'];
            $jewelryList = $this->jewelryModel->searchJewelry($search);
            $_POST['search'] = "";
        }

        $numOfPages = ceil(count($jewelryList) / 8);
        $pages = "<ul class='pagination'>";
        for($i = 1; $i <= $numOfPages; $i++) {
            $pages = $pages . "<li class='page-item'><a class='page-link' href='/jewelry?page$i=true'>$i</a></li>";
        }
        $pages = $pages . "</ul>";
        $items = '';


        $types = $this->jewelryModel->getTypes();
        $filters = "";
        for($j = 0; $j < count($types); $j++) {
            $filters = $filters . "<input type='radio' name='filter' value='" . $types[$j][0] . "'>" . $types[$j][0] . "<br>";
        }
        $preview = str_replace("{{ types }}", $filters, $preview);
        if(isset($_POST["filter"])) {
            $jewelryList = $this->jewelryModel->getByType($_POST["filter"]);
            $_POST['filter'] = "";
        }
        if(isset($_POST["ponisti"])) {
            $jewelryList = $this->jewelryModel->findAll();
        }
        $uri = $_SERVER['REQUEST_URI'];
        $id1 = trim($uri, '=true');

        $id1 = (int)trim($id1, '/jewelry?info');

        $userType = $this->getUsertype($user);
        echo $userType;
        for($i = 1; $i <= $numOfPages; $i++) {
            //if (isset($_GET['page' . $i])) {
                foreach (array_slice($jewelryList, ($i - 1) * 5, 5) as $jewelry) {
//                    switch ($userType) {
//                        case 'user' :
//                            echo 'user';
                    if($userType == 'user') {
                        $item = file_get_contents(Application::$ROOT_DIR . "/vebProjekat/src/view/jewelryItem.twig");
                        $item = str_replace('{{ infoAction }}', "info", $item);
                        $item = str_replace('{{ info }}', "info", $item);
                        $item = str_replace('{{ id }}', $jewelry[3], $item);
                        $item = str_replace("{{ price }}", $jewelry[1], $item);
                        $item = str_replace("{{ brand }}", $jewelry[2], $item);
                        $item = str_replace("{{ model }}", $jewelry[0], $item);
                        $items = str_replace('{{ pages }}', $pages, $items);
                        $items = str_replace('{{ action2 }}', 'Komentari', $items);
                        $items = str_replace('{{ action2Link }}', 'comments', $items);

                        $items = $items . $item;
                } else if($userType == 'employee' || $userType == 'admin') {
                        $item = file_get_contents(Application::$ROOT_DIR . "/vebProjekat/src/view/jewelryItem.twig");
                        $item = str_replace('{{ infoAction }}', "Promeni detalje", $item);
                        $item = str_replace('{{ info }}', "update", $item);
                        $item = str_replace('{{ id }}', $jewelry[3], $item);
                        $item = str_replace("{{ price }}", $jewelry[1], $item);
                        $item = str_replace("{{ brand }}", $jewelry[2], $item);
                        $item = str_replace("{{ model }}", $jewelry[0], $item);
                        $items = str_replace('{{ pages }}', $pages, $items);
                        $items = str_replace('{{ action2 }}', 'Obriši', $items);
                        $items = str_replace('{{ action2Link }}', 'delete', $items);

                        $items = $items . $item;
                    }
            }
        }
        if (isset($_GET['info'. $id1])) {
            if($user != "") {
                $info = $this->jewelryModel->jewelryInfo($id1);
                $preview = file_get_contents(Application::$ROOT_DIR . "/vebProjekat/src/view/jewelryInfo.twig");
                $preview = str_replace('{{ price }}', $info[1], $preview);
                $preview = str_replace('{{ model }}', $info[0], $preview);
                $preview = str_replace('{{ brand }}', $info[2], $preview);
                $preview = str_replace('{{ gender }}', $info[3], $preview);
                $preview = str_replace('{{ color }}', $info[4], $preview);
            } else {
                header("Location: /login");
            }
        }

        $deleteUri = $_SERVER['REQUEST_URI'];
        $deleteUri = trim($deleteUri, '=true');
        $deleteUri = (int)trim($deleteUri, '/jewelry?delete');
        if (isset($_GET['delete' . $deleteUri])) {
            $this->jewelryModel->deleteJewelry($deleteUri);
            header("Location: /jewelry");
        }


        $commentUri = $_SERVER['REQUEST_URI'];
        $commentUri = trim($commentUri, '=true');
        $commentUri = (int)trim($commentUri, '/jewelry?comments');
        if(isset($_GET['comments'. $commentUri])) {
            if($user != "") {
                $comments = $this->jewelryModel->getComments($commentUri);
                $items= file_get_contents(Application::$ROOT_DIR . "/vebProjekat/src/view/comments.twig");

                $allComments = $this->getComments($comments);
                $items = str_replace('{{ content }}', $allComments, $items);

                $this->setComment($commentUri, $user);
            } else {
                header("Location: /login");
            }
        }


        $preview = str_replace("{{ content }}", $items, $preview);
        return $preview;
    }


    public function insertJewelry() {
        $price = $_POST["price"];
        $model = $_POST["model"];
        $brand = $_POST["brand"];
        $gender = $_POST["gender"];
        $color = $_POST["color"];
        $type = $_POST["type"];

        var_dump( $_POST);
        if(isset($_FILES['image'])){

            $errors= array();
            $file_name = $_FILES['image']['name'];
            $file_size =$_FILES['image']['size'];
            $file_tmp =$_FILES['image']['tmp_name'];
            $file_type=$_FILES['image']['type'];

            move_uploaded_file($file_tmp, "images/$model.jpg");
        }else {
            echo "Error!";
        }

        $this->jewelryModel->insertJewelry($model, $price, $brand, $gender, $color, $type);

        header("Location: /jewelry");
    }

    public function getComments($comments) {
        $allComments = "";
        foreach($comments as $comm) {
            $comment = file_get_contents(Application::$ROOT_DIR . "/vebProjekat/src/view/comment.twig");
            $comment = str_replace('{{ name }}', $comm[1], $comment);
            $comm[1];
            $comment = str_replace('{{ comment }}', $comm[0], $comment);
            $allComments = $allComments . $comment;
        }

        return $allComments;
    }

    public function setComment($commentUri, $user) {
        if(!empty($_POST)) {
            if($user != "") {
                if($_POST['addComment'] != "") {
                    $this->jewelryModel->addComment($commentUri, $user, $_POST['addComment']);
                    $_POST["addComment"] = "";
                    header("Location: /jewelry?comments$commentUri=true");
                }
            }
        }
    }


}