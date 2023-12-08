<?php

namespace App\Main\Controller;

use App\Main\Model\Cats;
use App\Main\Forms\LoginForm;
use App\Main\Entity\CatsEntity;
use PhpParser\Node\Stmt\TryCatch;
use App\Core\Auth\AuthenticateException;
use App\Core\Controller\AbstractController;

class Start extends AbstractController
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

    
    public function onPost($POST){
        try{
            $auth = $this->getAuthenticate();
            $auth->login([
                'email' => $POST['email'],
                'password' => $POST['password']
            ]);
        }catch (AuthenticateException $Authenticate ){
            $this->erros[] =  [
                "error" => $Authenticate->getMessage()
            ];
        }

        if(count($this->erros) === 0){
            var_dump('ergg');
        }

        $this->InitMain();
    }
}