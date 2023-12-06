<?php

namespace App\Main\Collections;

class Role
{
    public $name;

    public function __construct(string $name)
    {
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
