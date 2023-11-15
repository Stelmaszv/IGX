<?php
require('../vendor/autoload.php');

use App\Core\Route\RouteMatch;
use App\Infrastructure\DB\Connect;

$connect = Connect::getInstance();
$routeMatch = new RouteMatch();
require('../route.php');

$routeMatch->setRoute();
?>

