<?php
use App\Core\Route\RouteMatch;
use App\Main\Controller\Start;

RouteMatch::addRoute(
    [
        'url' => '/',
        'Controller' => new Start,
        'name' => 'home'
    ]
);

RouteMatch::addRoute(
    [
        'url' => '/catse/{string:category}/{int:id}',
        'Controller' => new Start,
        'name' => 'vaw'
    ]
);

RouteMatch::addRoute(
    [
        'url' => '/cats/{string:category}/{int:id}',
        'Controller' => new Start,
        'name' => 'vqa'
    ]
);

RouteMatch::setHomeRouteIfNotActiveRoute();