<?php

namespace App\Main\Entity;

use App\Core\Model\ModelEntity;

class UserEntity implements ModelEntity
{
    private ?int $id;
    private ?string $name;
    private ?string $password;
    private ?string $email;
    private ?string $role;

    function __construct(string $name = null, string $password = null, string $email = null, string $role = null){
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->role = $role;
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

    public function getRole() : ?string
    {
        return $this->role;
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

    public function setRole($role) : object
    {
        $this->role = $role;

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
}
