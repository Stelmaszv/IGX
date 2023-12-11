<?php

namespace App\Main\Controller;

use App\Main\Model\User;
use App\Core\Controller\AbstractController;

class RegisterController extends AbstractController
{
    function InitMain() : void
    {   
        $this->createFormModel(User::class);
        $this->getForm();
                
        $this->setTemplate('../templete/register.html',[]);
        echo $this->getTemplate();
    }
}