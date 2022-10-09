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


        foreach ($jewelryList as $jewelry){
            $item = file_get_contents(Application::$ROOT_DIR . "/vebProjekat/src/view/jewelryItem.twig");
            $item = str_replace('{{ infoAction }}', "info", $item);
            $item = str_replace('{{ info }}', "info", $item);
            $item = str_replace('{{ id }}', $jewelry[3], $item);
            $item = str_replace("{{ price }}", $jewelry[1],$item);
            $item = str_replace("{{  brand }}", $jewelry[2],$item);
            $item = str_replace("{{ model }}", $jewelry[0],$item);

            $items = $items.$item;
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


}