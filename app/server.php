<?php

use App\Core\Controller\VuexTemplete;
use App\Core\Route\RouteMatch;
use App\Infrastructure\DB\Connect;
use App\Infrastructure\DB\Engines\MysqliEngin;
use App\Infrastructure\DB\Engines\PDOEngin;
use App\Main\Controller\Start;


RouteMatch::addRoute(
    [
        'url' => '/catse/{string:category}/{int:id}',
        'Controller' => new Start(),
        'name' => 'vaw'
    ]
);

RouteMatch::addRoute(
    [
        'url' => '/cats/{string:category}/{int:id}',
        'Controller' => new Start(),
        'name' => 'vqa'
    ]
);

RouteMatch::addRoute(
    [
        'url' => '/',
        'Controller' => new Start,
        'name' => 'home'
    ]
);

RouteMatch::setActiveRoute();