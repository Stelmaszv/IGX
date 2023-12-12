<?php

namespace App\Main\Controller;

use App\Main\Model\User;
use App\Main\Collections\Role;
use App\Core\Model\ModelValidateException;
use App\Core\Controller\AbstractController;
use App\Main\Collections\RolesMapCollection;

class RegisterController extends AbstractController
{   
    private array $erros = [];

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
                    'label' => "Register",
                    "class" => "form",
                    "type"  => "password",
                ]
            ],
            "div" => 'class',
        ]);
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
        
        $POST['roles'] = new RolesMapCollection(); 
        $POST['roles']->addRole(new Role('create'));

        try{
            $auth = $this->getAuthenticate();
            $auth->register($POST);
        }catch (ModelValidateException $modelValidateException){
            $this->erros[] =  [
                "error" => $modelValidateException->getMessage()
            ];
        }

        if(count($this->erros) === 0){
            var_dump('Register');
        }
        
        
        $this->InitMain();
  
    }

}