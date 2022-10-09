<?php

namespace vebProjekat\core;

class Controller
{
    public function render( $view, $message )
    {
        echo Application::$app->router->renderView( $view, $message );
    }

    public function getUseremail() {
        return Application::$app->router->getUserEmail();
    }

    public function getUsertype($email) {
        return Application::$app->router->getUserType($email);
    }
}