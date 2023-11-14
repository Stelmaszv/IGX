<?php
require('../vendor/autoload.php');

use App\Core\Route\RouteMatch;
use App\Infrastructure\DB\Connect;
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
var_dump($cats->get(161, [
    'name'
])->getName());
*/

echo '<pre>';
var_dump($cats->getAll(4));
echo '</pre>';

$routeMatch->setRoute();
?>

