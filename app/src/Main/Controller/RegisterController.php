<?php

namespace App\Main\Controller;

use App\Main\Model\User;
use App\Core\Controller\AbstractController;

class RegisterController extends AbstractController
{
    function InitMain() : void
    {   
        $this->createFormModel(User::class,[
            "exclude" => [
                "salt"
            ],
            "submit" => [
                'type' => 'submit',
                'label' => "Register",
                'class' => 'btn'
            ],
            "fields" => [
                "password" => [
                    "class" => "form",
                    "type"  => "password",
                ]
            ],
            "div" => 'class'
        ]);
        $this->getForm();
                
        $this->setTemplate('../templete/register.html',[
            'form' => $this->genrateForm([
                'method' => 'POST',
                'class' => 'btn'
            ]),
        ]);
        echo $this->getTemplate();
    }

    function onPost(array $POST): void
    {
        $this->InitMain();
    }
}