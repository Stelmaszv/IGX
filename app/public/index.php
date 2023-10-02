<?php
use App\Core\Route\RouteMatch;
require('../vendor/autoload.php');

RouteMatch::match(
'/cats/{string:category}/{int:id}'
);
?>

