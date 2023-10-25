<?php

namespace App\Core\Model;
use App\Infrastructure\DB\Connect;
use App\Infrastructure\DB\DBInterface;

abstract class AbstractModel
{
    private array $fields;
    private DBInterface $engin;
    public MigrationBuilder $migrationBuilder;

    public function __construct()
    {
        $connect = Connect::getInstance();
        $this->engin = $connect->getEngin();
        $this->migrationBuilder = new MigrationBuilder($this->engin);
    }

    abstract protected function initFields() : void;

    protected function addField(Field $field) : void
    {
        $this->fields[] = $field;
    }

    public function initModel(): void
    {
        $this->initFields();
        $this->migrationBuilder->setName(get_class($this));
        $this->migrationBuilder->setFields($this->fields);
        $this->migrationBuilder->build();
    }

}