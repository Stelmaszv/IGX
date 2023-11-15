<?php
require('../vendor/autoload.php');

use App\Core\Model\QueryBuilder\Between;
use App\Core\Model\QueryBuilder\Query;
use App\Core\Model\QueryBuilder\Where;
use App\Core\Route\RouteMatch;
use App\Infrastructure\DB\Connect;
use App\Main\Entity\CatsEntity;
use App\Main\Model\Cats;

$connect = Connect::getInstance();
$routeMatch = new RouteMatch();
require('../route.php');

$routeMatch->setRoute();
?>

