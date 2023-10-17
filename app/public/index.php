<?php
require('../vendor/autoload.php');

use App\Core\Model\AbstractModel;
use App\Core\Route\RouteMatch;
use App\Infrastructure\DB\Connect;

$connect = Connect::getInstance();
define('CONNECT', serialize($connect));
$routeMatch = new RouteMatch();
require('../route.php');

$routeMatch->setRoute();
?>

