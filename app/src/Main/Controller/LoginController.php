<?php

namespace App\Main\Controller;
use App\Core\GenericController\GenericAuthLogin;

class LoginController extends GenericAuthLogin
{
    protected string $template = '../templete/login.html';
    protected array $formSettings = [
        'method' => 'POST',
        'class' => 'btn'
    ];

    protected function actionAfterLogin(array $POST){
        var_dump('login');
    }
}