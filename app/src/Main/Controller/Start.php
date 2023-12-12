<?php

namespace App\Main\Controller;

use App\Core\Controller\AbstractController;

class Start extends AbstractController
{
    function InitMain() : void
    {   
        $this->setTemplate('../templete/home.html',
            [
            'form' => $this->genrateForm([
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