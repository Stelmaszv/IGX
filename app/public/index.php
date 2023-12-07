<?php
session_start();
require('../vendor/autoload.php');

use App\Main\Model\Cats;
use App\Core\Route\RouteMatch;
use App\Main\Collections\Role;
use App\Core\Auth\Authenticate;
use App\Main\Entity\CatsEntity;
use App\Infrastructure\DB\Connect;
use App\Main\Collections\RolesMapCollection;

$routeMatch = new RouteMatch();
require('../route.php');
$routeMatch->setRoute();


$connect = Connect::getInstance();
$engin = $connect->getEngine();


$authenticate = new Authenticate($engin);
if(isset($_GET['login'])){
    $authenticate->login([
        "email" => "email@citki.com",
        "password" => "password"
    ]);
}


$rolesMapCollection = new RolesMapCollection;
$rolesMapCollection->addRole(new Role('update'));
$rolesMapCollection->addRole(new Role('create'));


if(isset($_GET['register'])){
    $authenticate->register([
        "name" => "user",
        "password" => "password",
        "email" => "email@citki.com",
        "roles" => $rolesMapCollection
    ]);
}
