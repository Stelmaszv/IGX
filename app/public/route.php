<?php
session_start();
require('../vendor/autoload.php');

use App\Core\Route\RouteMatch;
use App\Main\Collections\Role;
use App\Core\Auth\Authenticate;
use App\Infrastructure\DB\Connect;
use App\Main\Collections\RolesMapCollection;

$routeMatch = new RouteMatch();
require('../route.php');

$routeMatch->setRoute();

$connect = Connect::getInstance();
$engin = $connect->getEngine();


$authenticate = new Authenticate($engin);

if(isset($_GET['logout'])){
    $authenticate->logout();
}

$rolesMapCollection = new RolesMapCollection;
$rolesMapCollection->addRole(new Role('acces'));
$rolesMapCollection->addRole(new Role('update'));

if(isset($_GET['login'])){
    $authenticate->register([
        "name" => "user",
        "password" => "password",
        "email" => "email@citki.com",
        "roles" => $rolesMapCollection
    ]);
}

if($authenticate->inLogin()){
    var_dump($authenticate->getUser());
    echo '<br>';
    echo '<a href="?logout">logout</a>';
    echo '<br>';
}else{
    echo '<br>';
    echo '<a href="?login">loguj</a>';
    echo '<br>';
}