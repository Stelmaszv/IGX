<?php

namespace App\Main\Controller;

use App\Core\GenericController\GenericAuthRegister;

class RegisterController extends GenericAuthRegister
{   
    protected string $template = '../templete/register.html';
    protected array $formSettings = [
        'method' => 'POST',
        'class' => 'btn'
    ];

    public function actionAfterRegister(array $postData){
        var_dump('Register');
    }
}