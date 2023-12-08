<?php

namespace App\Main\Controller;

use App\Core\Controller\AbstractController;

class LoginController extends AbstractController
{
    protected ?string $role = 'login';

    function InitMain() : void
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