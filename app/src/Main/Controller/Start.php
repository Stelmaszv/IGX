<?php

namespace App\Main\Controller;

use App\Core\Controller\AbstractController;
use App\Core\Route\RouteMatch;

class Start extends AbstractController
{
    function main() : void
    {
        $this->setTemplete('../templete/home.html',
            [
            'url'     =>   RouteMatch::getRouteAsObject('vaw',
                ["category"=>"feqfqef","id"=>1],
            )->getUrl(),
            'name'     =>   $this->getRoute()->getName(),
            'loop'    => [
                ["number" => 1],
                ["number" => 2]
            ],
            'zero'    => true
            ]
        );
        echo $this->getTemplete();
    }
}