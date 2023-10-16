<?php

use App\Main\Controller\Start;

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
        'url' => '/catse/{string:category}/{int:id}',
        'Controller' => new Start(),
        'name' => 'qefeqf'
    ]
);