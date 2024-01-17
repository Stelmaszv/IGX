<?php

namespace App\Core\GenericController;

use App\Core\Controller\AbstractController;

abstract class GenericAuthRegister extends AbstractController
{
    protected string  $template = '';
    protected array $formSettings = [
        'method' => 'POST',
        'class' => 'btn'
    ];

    function initMain() : void
    {   
        $this->createRegisterForm();
        $this->getForm();
    
        $this->setTemplate($this->template,[
            'form' => $this->generateForm($this->formSettings),
            'erros' => $this->errors
        ]);

        echo $this->getTemplate();
    }

    public function onPost($postData) : void
    {
        if($this->fromAuthActionRegister($postData)){
            $this->actionAfterRegister($postData);
        }

        $this->InitMain();
    }

    abstract public function actionAfterRegister(array $postData);
}
