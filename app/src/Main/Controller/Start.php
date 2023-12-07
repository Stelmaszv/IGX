<?php

namespace App\Main\Controller;

use App\Main\Model\Cats;
use App\Main\Entity\CatsEntity;
use App\Core\Controller\AbstractController;

class Start extends AbstractController
{
    function main() : void
    {        
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