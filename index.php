<?php

require_once __DIR__.'/vendor/autoload.php';

use vebProjekat\controller\JewelryController;
use vebProjekat\controller\LoginController;
use vebProjekat\controller\RegisterController;
use vebProjekat\controller\UserController;
use vebProjekat\core\Application;
$app = new Application(dirname(__DIR__));

$app->router->get('/', 'home');
$app->router->get('/login','login');
$app->router->post('/login', [LoginController::class,'checkUser']);
$app->router->get('/logout', [LoginController::class,'logout']);
$app->router->get('/jewelry', [JewelryController::class,'getJewelryList']);
$app->router->post('/jewelry', [JewelryController::class, 'getJewelryList']);
$app->router->get('/users', [UserController::class, 'getUsers']);
$app->router->get('/addPerson', [UserController::class, 'addPerson']);
$app->router->post('/addPerson', [UserController::class, 'addPerson']);
$app->router->get('/updateProfile', [UserController::class, 'updateUser']);
$app->router->post('/updateProfile', [UserController::class, 'updateUser']);
$app->router->get('/report', [UserController::class, 'listOfUsers']);
$app->router->get('/register', 'register');
$app->router->post('/register', [RegisterController::class, 'register']);
$app->router->get('/insertJewelry', 'insertJewelry');
$app->router->post('/insertJewelry', [JewelryController::class, 'insertJewelry']);

$app->run();

