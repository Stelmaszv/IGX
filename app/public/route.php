<?php
session_start();
require('../vendor/autoload.php');

use App\Main\Model\User;
use App\Core\Route\RouteMatch;
use App\Main\Collections\Role;
use App\Core\Auth\Authenticate;
use App\Infrastructure\DB\Connect;
use App\Main\Collections\RolesMapCollection;

$routeMatch = new RouteMatch();


$connect = Connect::getInstance();
$engin = $connect->getEngine();


$authenticate = new Authenticate($engin);
if(isset($_GET['register'])){
    $authenticate->register([
        "name" => "user",
        "password" => "password",
        "email" => "email@citki.com",
        "roles" => $rolesMapCollection
    ]);
}

if(isset($_GET['login'])){
    $authenticate->login([
        "email" => "email@citki.com",
        "password" => "password"
    ]);
}

require('../route.php');

$routeMatch->setRoute();

if(isset($_GET['logout'])){
    $authenticate->logout();
}

$rolesMapCollection = new RolesMapCollection;
$rolesMapCollection->addRole(new Role('update'));
$rolesMapCollection->addRole(new Role('create'));



if($authenticate->inLogin()){
    echo '<pre>';
    //var_dump($authenticate->getUser());
    echo '</pre>';
    echo '<br>';
    echo '<a href="route.php/login">logout</a>';
    echo '<br>';
}else{
    echo '<br>';
    echo '<a href="route.php/login">loguj</a>';
    echo '<br>';
}