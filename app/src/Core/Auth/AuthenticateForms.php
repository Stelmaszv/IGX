<?php

namespace App\Core\Auth;

use App\Settings\AuthenticateSettings;

trait AuthenticateForms
{
    private $model = AuthenticateSettings::MODEL;

    public function createRegisterForm(array $modificationAddon = [])  : void
    {
        $modificationDefault = [
            "exclude" => ["salt", "roles"],
            "submit" => ['type' => 'submit', 'label' => "Register", 'class' => 'btn'],
            "fields" => ["password" => ["class" => "form", "type" => "password"]],
            "div" => 'class'
        ];

        $this->formBulider->createFormModel(new $this->model($this->engine),array_merge($modificationDefault, $modificationAddon));
    }

    public function createLoginForm(array $modificationAddon = [])  : void
    {

        $modificationDefault = [
            "exclude" => ["salt", "roles", "name"],
            "submit" => ['type' => 'submit', 'label' => "Login", 'class' => 'btn'],
            "fields" => ["password" => ["class" => "form", "type" => "password"]],
            "div" => 'class'
        ];

        $this->formBulider->createFormModel(new $this->model($this->engine),array_merge($modificationDefault, $modificationAddon));
    }

    public function createAuthFromModel(array $modificationAddon = [], $id = null) : void
    {

        $modificationDefault = [
            "exclude" => ["salt", "roles"],
            "submit" => ['type' => 'submit', 'label' => "Register", 'class' => 'btn'],
            "div" => 'class'
        ];

        if($id !== null){
            $modificationDefult['exclude'][] = 'password';
        }

        $this->formBulider->createFormModel(new $this->model($this->engine),array_merge($modificationDefault, $modificationAddon),$id);
    }
}

