<?php

namespace App\Main\Controller;

use App\Main\Model\User;
use App\Main\Collections\Role;
use App\Core\Auth\Authenticate;
use App\Core\Controller\AbstractController;
use App\Main\Collections\RolesMapCollection;

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