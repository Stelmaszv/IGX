<?php

namespace App\Main\Controller;

use App\Core\Controller\AbstractController;

class RegisterController extends AbstractController
{   
    function InitMain() : void
    {   
        $this->createRegisterForm();
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
        
        if($this->fromActionRegister($POST)){
            var_dump('Register');
        }

        $this->InitMain();
  
    }

}