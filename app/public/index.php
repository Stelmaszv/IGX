<?php
require('../vendor/autoload.php');

use App\Core\Route\RouteMatch;
use App\Infrastructure\DB\Connect;
use App\Main\Entity\CatsEntity;
use App\Main\Model\Cats;

$connect = Connect::getInstance();
$routeMatch = new RouteMatch();
require('../route.php');

$cats = new Cats();
/*
$cats->add(new CatsEntity(
    'fw',
    75,
    'fe qfeqf'
));
*/
var_dump($cats->get(161,['name'])->getName());

$routeMatch->setRoute();
?>

