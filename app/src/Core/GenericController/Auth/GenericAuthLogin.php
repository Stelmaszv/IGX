<?php

namespace App\Core\GenericController\Auth;

use App\Core\Controller\AbstractController;

abstract class GenericAuthLogin extends AbstractController
{
    protected string  $template = '';
    protected array $formSettings = [
        'method' => 'POST',
        'class' => 'btn'
    ];

    public function initMain() : void
    {   
        $this->createLoginForm();
        $this->getForm();
    
        $this->setTemplate($this->template,[
            'form' => $this->generateForm($this->formSettings),
            'erros' => $this->errors
        ]);

        echo $this->getTemplate();
    }

    public function onPost(array $postData) : void
    {
        if($this->fromAuthActionLogin($postData)){
            $this->actionAfterLogin($postData);
        }

        $this->InitMain();
    }

    abstract public function actionAfterLogin(array $postData);
}
