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

    public function actionAfterLogin(array $postData){
        var_dump('login');
    }
}