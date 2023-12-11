<?php

namespace App\Main\Controller;

use App\Main\Forms\LoginForm;
use App\Core\Auth\AuthenticateException;
use App\Core\Controller\AbstractController;

class LoginController extends AbstractController
{
    private array $erros = [];

    function InitMain() : void
    {   
        $this->setForm(LoginForm::class);
        $this->getForm();

        $this->setTemplate('../templete/home.html',
            [
            'form' => $this->genrateForm([
                'method' => 'POST',
                'class' => 'btn'
            ]),
            'name' => $this->getRoute()->getName(),
            'zero' => true,
            'erros' => $this->erros
            ]
        );

        echo $this->getTemplate();
    }

    
    public function onPost($POST) : void
    {

        try{
            $auth = $this->getAuthenticate();
            $auth->login($POST);
        }catch (AuthenticateException $Authenticate ){
            $this->erros[] =  [
                "error" => $Authenticate->getMessage()
            ];
        }

        if(count($this->erros) === 0){
            var_dump('Login');
        }

        $this->InitMain();
    }
}