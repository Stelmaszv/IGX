<?php
session_start();
require('../vendor/autoload.php');

use App\Core\Route\RouteMatch;
use App\Core\Auth\Authenticate;
use App\Infrastructure\DB\Connect;

$routeMatch = new RouteMatch();
require('../route.php');

$routeMatch->setRoute();

$connect = Connect::getInstance();
$engin = $connect->getEngine();


$authenticate = new Authenticate($engin);

if(isset($_GET['logout'])){
    $authenticate->logout();
}

if(isset($_GET['login'])){
    $authenticate->register([
        "name" => "user",
        "password" => "password",
        "email" => "email@citki.com",
        "roles" => ['acces']
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