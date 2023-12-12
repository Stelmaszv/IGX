<?php

namespace App\Main\Controller;

use App\Main\Model\User;
use App\Core\Auth\AuthenticateException;
use App\Core\Model\ModelValidateException;
use App\Core\Controller\AbstractController;

class RegisterController extends AbstractController
{   
    private array $erros = [];

    public function InitMain() : void
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

    public function onPost(array $POST): void
    {   
        $POST['roles'] = []; 
        try{
            $auth = $this->getAuthenticate();
            $auth->register($POST);
        }catch (ModelValidateException $ModelValidateException ){
            $this->erros[] =  [
                "error" => $ModelValidateException->getMessage()
            ];
        }
        $this->InitMain();
    }
}