<?php
use App\Core\Route\RouteMatch;
use App\Main\Controller\Start;

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

RouteMatch::getActiveRoute();