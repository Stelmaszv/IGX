<?php

namespace App\Main\Controller;
use App\Core\Controller\AbstractController;

class LoginController extends AbstractController
{
    public function InitMain() : void
    {   
        $this->createLoginForm();
        $this->getForm();
    

        $this->setTemplate('../templete/Register.html',[
            'form' => $this->genrateForm([
                'method' => 'POST',
                'class' => 'btn'
            ]),
            'erros' => $this->erros
        ]);

        echo $this->getTemplate();
    }

    public function onPost($POST) : void
    {
        if($this->fromActionLogin($POST)){
            var_dump('Login');
        }

        $this->InitMain();
    }
}