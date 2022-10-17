<?php

namespace vebProjekat\core;

use function Composer\Autoload\includeFile;
class Router {


    public array $routes = [];
    public Request $request;
    public Response $response;

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback) {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['post'][$path] = $callback;
    }
    public function resolve() {
        $path  = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if ( $callback === false ) {
            $this->response->setResponse(404);

            return $this->renderView( '_404', $path );
        }

        if ( is_string($callback) ) {
            return $this->renderView($callback, $path);
        }

        if(is_array($callback)) {
            $callback[0] = new $callback[0]();
        }
        return call_user_func($callback);
    }


    public function renderView($view, $params = []) {

        $content = $this->getPageContent($view);
        $navbarContent = $this->getLayout('navbar');
        $returnView = $this->getContent("defaultLayout");
//        $returnView = str_replace('{{ navbar }}', $navbarContent, $returnView);
        $returnView = str_replace("{{ content }}", $content, $returnView);

        if ( $this->getSessionStatus() ) {
            $navbarContent = str_replace('{{ loggedOut }}', 'hidden', $navbarContent );
        } else {
            $navbarContent = str_replace('{{ loggedIn }}', 'hidden', $navbarContent );
        }
        if ($this->getUserType($this->getUserEmail()) == "admin" || $this->getUserType($this->getUserEmail()) == "employee") {
            $navbarContent = str_replace('{{ radnik }}', '', $navbarContent );
        } else if ($this->getUserType($this->getUserEmail()) == "customer" || $this->getUserType($this->getUserEmail()) == "") {
            $navbarContent = str_replace('{{ admin }}', 'hidden', $navbarContent );
            $navbarContent = str_replace('{{ radnik }}', 'hidden', $navbarContent );
        }
        if($this->getUserType($this->getUserEmail()) == "admin") {
            $navbarContent = str_replace('{{ admin }}', '', $navbarContent);
        } else {
            $navbarContent = str_replace('{{ admin }}', 'hidden', $navbarContent);
        }


        $returnView = str_replace('{{ navbar }}', $navbarContent, $returnView);
        if (isset($params["message"] ) ) {
            $returnView = str_replace('{{ errorStatus }}', "", $returnView);
            $returnView = str_replace('{{ error }}', $params["message"], $returnView);
        } else {
            $returnView = str_replace('{{ errorStatus }}', "hidden", $returnView);
            $returnView = str_replace('{{ error }}', "", $returnView);
        }

        return $returnView;
    }
    public function getContent($name) {
        ob_start();
        include_once (Application::$ROOT_DIR."/vebProjekat/src/view/$name.php");
        return ob_get_clean();
    }
    public function getLayout($name) {
        ob_start();
        include_once (Application::$ROOT_DIR."/vebProjekat/src/view/$name.twig");
        return ob_get_clean();
    }

    public function getPageContent($view) {
        ob_start();
        if($view == "_404") {
            $category = "_404";
        }
        include_once (Application::$ROOT_DIR."/vebProjekat/src/view/$view.twig");
        return ob_get_clean();
    }

    public function getUserEmail() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ( isset($_SESSION["email"]) ) {
            return $_SESSION["email"];
        }
        return "";
    }


    public function getUserType($email) {
        $db = Database::connect();
        $q = "SELECT 
                user_type.type
            FROM user
            INNER JOIN user_type ON user.type_id = user_type.id
            WHERE user.email='$email';";

        $query = mysqli_query($db, $q);
        $result = mysqli_fetch_assoc( $query);

        if($result != NULL) {
            return $result['type'];
        } else {
            return "";
        }
    }

    public function getSessionStatus() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ( isset($_SESSION["loggedStatus"]) ) {
            if ($_SESSION["loggedStatus"] == true) {
                return true;
            }
        }
        return false;
    }
}