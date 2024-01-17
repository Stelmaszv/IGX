<?php

namespace App\Main\Controller;

use App\Core\GenericController\GenercicAuthRegister;

class RegisterController extends GenercicAuthRegister
{   
    protected string $template = '../templete/register.html';
    protected array $formSettings = [
        'method' => 'POST',
        'class' => 'btn'
    ];

    protected function actionAfterRegister(array $POST){
        var_dump('Register');
    }
}