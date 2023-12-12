<?php

namespace App\Main\Collections;

use App\Main\Collections\Role;
use App\Core\Model\MapCollection;

class RolesMapCollection implements MapCollection
{
    private array $roles;
    
    public function getRoles()
    {
        return $this->roles;
    }

    public function addRole(Role $role)
    {
        $this->roles[] = $role;
    }

    public function map() : string 
    {        
        return json_encode(array_map(function(Role $el){
            return $el->getName();
        },$this->roles));
    }
 
}
