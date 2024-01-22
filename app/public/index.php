<?php

use App\Core\Route\RouteMatch;
session_start();
require('../vendor/autoload.php');


$routeMatch = new RouteMatch();
require('../route.php');
$routeMatch->setRoute();
?>
