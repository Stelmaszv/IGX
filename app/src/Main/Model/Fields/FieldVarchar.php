<?php

namespace App\Main\Model\Fields;

class FieldVarchar
{
    private string $name;
    private int $length;

    public function __construct(string $name,int $length){
        $this->name = $name;
        $this->length = $length;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLength(): int
    {
        return $this->length;
    }
}