<?php

namespace App\Main\Forms;

use App\Core\Form\Input;
use App\Core\Form\Button;
use App\Core\Form\AbstractForm;

class LoginForm extends AbstractForm
{

    protected function initFields() : void
    {
        $this->addField(new Input(
            [
                'type' => 'text',
                'name' => "email",
                'id' => "email",
                'label' => "Podaj Login : ",
                'divClass' => 'fqeff',
                'class' => 'form'
            ]
        ));

        $this->addField(new Input(
            [
                'type' => 'password',
                'name' => "password",
                'id' => "password",
                'label' => "Podaj Password : ",
                'divClass' => 'fqeff',
                'class' => 'form'
            ]
        ));

        $this->addField(new Button(
            [
                'type' => 'submit',
                'label' => "Loguj",
                'class' => 'btn'
            ]
        ));
    }
}
