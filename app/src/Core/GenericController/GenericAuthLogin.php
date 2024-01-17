<?php

namespace App\Core\GenericController;

use App\Core\Controller\AbstractController;

abstract class GenericAuthLogin extends AbstractController
{
    protected string  $template = '';
    protected array $formSettings = [
        'method' => 'POST',
        'class' => 'btn'
    ];

    public function InitMain() : void
    {   
        $this->createLoginForm();
        $this->getForm();
    

        $this->setTemplate($this->template,[
            'form' => $this->genrateForm($this->formSettings),
            'erros' => $this->erros
        ]);

        echo $this->getTemplate();
    }

    public function onPost(array $POST) : void
    {
        if($this->fromActionLogin($POST)){
            $this->actionAfterLogin($POST);
        }

        $this->InitMain();
    }

    protected function actionAfterLogin(array $POST){}
}
