<?php

namespace App\Main\Entity;

use Exception;
use App\Settings\RolesList;
use App\Main\Collections\Role;
use App\Core\Model\ModelEntity;
use App\Main\Collections\RolesMapCollection;

class UserEntity implements ModelEntity
{
    private ?int $id;
    private ?string $name;
    private ?string $password;
    private ?string $email;
    private ?string $roles;
    private ?string $salt;

    function __construct(
        string $name = null,
        string $password = null, 
        string $email = null, 
        string $roles = null, 
        string $salt = null
    ){
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->roles = $roles;
        $this->salt = $salt;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getName()  : ?string
    {
        return $this->name;
    }

    public function getEmail() : ?string
    {
        return $this->email;
    }

    public function getRoles() : RolesMapCollection
    {
        $rolesMap =  new RolesMapCollection();
        foreach(json_decode($this->roles) as $role){
            $rolesMap->addRole(new Role($role));   
        }

        return $rolesMap;
    }

    public function getRolesAsJSON() : array
    {
        return json_decode($this->roles);
    }

    public function addRole(string $role){
        $roles = json_decode($this->roles);

        if(!in_array($role,RolesList::ROLES)){
            throw new Exception('Invalid Role "'.$role.'" !');
        }

        $roles[] = $role;

        $this->roles = json_encode($roles);
    }

    public function hasRole(string $role) : bool
    {
        return in_array($role,json_decode($this->roles));
    }

    public function setName($name) : object
    {
        $this->name = $name;

        return $this;
    }
 
    public function setEmail($email) : object
    {
        $this->email = $email;

        return $this;
    }

    public function setRoles($roles) : object
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword() : ?string
    {
        return $this->password;
    }

    public function setPassword($password) : object
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }
}
