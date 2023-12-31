<?php

use App\Main\Controller\Start;
use App\Main\Controller\LoginController;
use App\Main\Controller\RegisterController;


$routeMatch->addRoute(
    [
        'url' => '/register',
        'Controller' => new RegisterController(),
        'name' => 'register'
    ]
);

$routeMatch->addRoute(
    [
        'url' => '/catese/{string:category}/{int:id}',
        'Controller' => new Start(),
        'name' => 'vaw',
    ]
);

$routeMatch->addRoute(
    [
        'url' => '/',
        'Controller' => new Start(),
        'name' => 'home',
        'home' => true
    ]
);

$routeMatch->addRoute(
    [
        'url' => '/login',
        'Controller' => new LoginController(),
        'name' => 'login'
    ]
);