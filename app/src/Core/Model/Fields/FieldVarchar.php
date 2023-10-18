<?php

namespace App\Core\Model\Fields;

class FieldVarchar
{
    private string $name;
    private int $length;

    public function __construct(string $name,int $length,bool $isNull){
        $this->name = $name;
        $this->length = $length;
        $this->isNull = $isNull;
    }

    public function isNull(): bool
    {
        return $this->isNull;
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