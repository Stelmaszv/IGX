<?php

namespace App\Main\Controller;

use App\Core\Controller\AbstractController;
use App\Main\Model\Cats;

class Start extends AbstractController
{
    function main() : void
    {
        $cats = new Cats();
        $cats->add(
            [
                "description" => "hteheth",
                "name" => "dyzio",
                "counter" => 13,
            ]
        );


        $this->setTemplate('../templete/home.html',
            [
            'name' => $this->getRoute()->getName(),
            'loop' => [
                ["number" => 1],
                ["number" => 2]
            ],
            'zero' => true
            ]
        );

        echo $this->getTemplate();
    }
}