<?php

namespace App\Core\Model;
use App\Infrastructure\DB\DBInterface;

abstract class AbstractModel
{
    private array $fields;
    private DBInterface $engin;

    public function __construct()
    {
        $this->engin = unserialize(CONNECT)->getEngin();
    }

    abstract protected function initFields() : void;

    protected function addField(mixed $field) : void
    {
        $this->fields[] = $field;
    }

    public function initModel(): void
    {
        $this->initFields();

    }
}