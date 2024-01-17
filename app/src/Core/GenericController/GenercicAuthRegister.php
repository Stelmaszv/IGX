<?php

namespace App\Core\GenericController;

use App\Core\Controller\AbstractController;

abstract class GenercicAuthRegister extends AbstractController
{
    protected string  $template = '';
    protected array $formSettings = [
        'method' => 'POST',
        'class' => 'btn'
    ];

    function InitMain() : void
    {   
        $this->createRegisterForm();
        $this->getForm();
    
        $this->setTemplate($this->template,[
            'form' => $this->genrateForm($this->formSettings),
            'erros' => $this->erros
        ]);

        echo $this->getTemplate();
    }

    
    public function onPost($POST) : void
    {
        if($this->fromActionRegister($POST)){
            var_dump('Register');
        }

        $this->InitMain();
    }

    protected function actionAfterLogin(array $POST){}
}
