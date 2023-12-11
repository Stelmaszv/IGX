<?php

namespace App\Main\Controller;

use App\Core\Controller\AbstractController;

class RegisterController extends AbstractController
{
    function InitMain() : void
    {   
        $this->setTemplate('../templete/register.html',[]);
        echo $this->getTemplate();
    }

}