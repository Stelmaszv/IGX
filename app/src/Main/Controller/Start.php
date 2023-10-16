<?php

namespace App\Main\Controller;

use App\Core\Controller\AbstractController;
use App\Core\Route\RouteMatch;
use ReflectionClass;


class Start extends AbstractController
{
    function main() : void
    {
        /*
        $this->setTemplete('../templete/home.html',
            [
            'name' => $this->getRoute()->getName(),
            'loop' => [
                ["number" => 1],
                ["number" => 2]
            ],
            'zero' => true
            ]
        );
        echo $this->getTemplete();
        */
        var_dump($this->getRoute()->getName());






    }
}