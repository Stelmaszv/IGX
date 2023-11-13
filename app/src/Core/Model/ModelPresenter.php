<?php

namespace App\Core\Model;

class ModelPresenter
{
    private array $fields;

    function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}