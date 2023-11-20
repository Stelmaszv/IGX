<?php
require('../vendor/autoload.php');

use App\Core\Route\RouteMatch;
use App\Infrastructure\DB\Connect;

$routeMatch = new RouteMatch();
require('../route.php');

$routeMatch->setRoute();

$connect = Connect::getInstance();
$connect->getEngine()->runQuery("INSERT INTO `Cats` (`id`, `name`, `counter`, `description`) VALUES (NULL, 'hetehte', '32424', 'rgegg');");