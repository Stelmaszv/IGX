<?php
require('../vendor/autoload.php');

use App\Core\Route\RouteMatch;
use App\Infrastructure\DB\Connect;
use App\Main\Model\Cats;

$connect = Connect::getInstance();
$routeMatch = new RouteMatch();
require('../route.php');

$cats = new Cats();
$cats->change(
    [
        "name" => "margolcia",
        "counter" => 156
    ],
    156
);

$routeMatch->setRoute();
?>

