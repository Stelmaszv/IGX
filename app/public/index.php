<?php
session_start();
require('../vendor/autoload.php');

use App\Core\Route\RouteMatch;

$routeMatch = new RouteMatch();
require('../route.php');
$routeMatch->setRoute();
?>
