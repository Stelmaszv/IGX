<?php
require('../vendor/autoload.php');

use App\Main\Model\User;
use App\Core\Route\RouteMatch;
use App\Main\Entity\UserEntity;
use App\Infrastructure\DB\Connect;

$routeMatch = new RouteMatch();
require('../route.php');

$routeMatch->setRoute();

$connect = Connect::getInstance();
$user = new User();
$user->add(new UserEntity(
    "user",
    password_hash('password', PASSWORD_DEFAULT),
    "email@citki.com",
    "role",
));