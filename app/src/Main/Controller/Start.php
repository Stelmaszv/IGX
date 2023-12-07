<?php

namespace App\Main\Controller;

use App\Main\Model\User;
use App\Main\Collections\Role;
use App\Core\Auth\Authenticate;
use App\Core\Controller\AbstractController;
use App\Main\Collections\RolesMapCollection;

class Start extends AbstractController
{
    function main() : void
    {
        $rolesMapCollection = new RolesMapCollection;
        $rolesMapCollection->addRole(new Role('update'));
        $rolesMapCollection->addRole(new Role('create'));

        $authenticate = new Authenticate($this->engine);

        
        
        $auth = $authenticate->getUser();
        $user = new User;
        $auth->setEmail('email@citki.com');
        $user->change($auth,$auth->getId());
        
        if(!$authenticate->inLogin()){
            $authenticate->register([
                "name" => "user",
                "password" => "password",
                "email" => "email@citki.com",
                "roles" => $rolesMapCollection
            ]);
        }else{
            $authenticate->login([
                "email" => "email@citki.com",
                "password" => "password",
            ]);
        }

        
        $this->setTemplate('../templete/home.html',
            [
            'name' => $this->getRoute()->getName(),
            'loop' => [
                ["number" => 1],
                ["number" => 2]
            ],
            'zero' => true
            ]
        );

        echo $this->getTemplate();
    }
}