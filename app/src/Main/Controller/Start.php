<?php

namespace App\Main\Controller;

use App\Main\Model\User;
use App\Core\Controller\AbstractController;
use App\Main\Entity\UserEntity;

class Start extends AbstractController
{
    function InitMain() : void
    {   
        $this->setTemplate('../templete/home.html',
            [
            'form' => $this->generateForm([
                'method' => 'POST',
                'class' => 'btn'
            ]),
            'name' => $this->getRoute()->getName(),
            'zero' => true
            ]
        );

        echo $this->getTemplate();
    }

}