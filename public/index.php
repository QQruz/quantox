<?php

require '../config.php';
require '../vendor/autoload.php';

use App\Routing\Router;

$router = new Router;

$router->get('/', 'StudentController', 'index');
$router->get('/students/:id', 'StudentController', 'show');

$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];

$router->run($method, $url);