<?php
require('../vendor/autoload.php');

use App\Core\Model\AbstractModel;
use App\Core\Route\RouteMatch;
use App\Infrastructure\DB\Connect;
use App\Main\Model\Cats;

$connect = Connect::getInstance();
$routeMatch = new RouteMatch();
require('../route.php');

$routeMatch->setRoute();
?>

