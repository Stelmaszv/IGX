<?php
use App\Core\Route\RouteMatch;

$routeMatch = new RouteMatch();
require('route.php');
$routeMatch->setRoute();

/*
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
*/

//RouteMatch::setActiveRoute();