<?php

namespace App\Main\Collections;

use Exception;
use App\Settings\RolesList;

class Role
{
    public $name;

    public function __construct(string $name)
    {
        if(!in_array($name,RolesList::ROLES)){
            throw new Exception('Invalid Role "'.$name.'" !');
        }

        $this->name = $name;
    }
 
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
