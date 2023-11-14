<?php
require('../vendor/autoload.php');

use App\Core\Model\QueryBuilder\AddWhere;
use App\Core\Model\QueryBuilder\Between;
use App\Core\Model\QueryBuilder\Query;
use App\Core\Model\QueryBuilder\Where;
use App\Core\Route\RouteMatch;
use App\Infrastructure\DB\Connect;
use App\Main\Model\Cats;

$connect = Connect::getInstance();
$routeMatch = new RouteMatch();
require('../route.php');

$cats = new Cats();

$sql = new Query();

$sql->addWhere(new Between("counter",76,145,'and'));

var_dump($cats->getFiltered($sql));



$routeMatch->setRoute();
?>

