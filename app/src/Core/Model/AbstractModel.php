<?php

namespace App\Core\Model;
use App\Infrastructure\DB\Connect;
use App\Infrastructure\DB\DBInterface;

abstract class AbstractModel
{
    use CRUD;
    private array $fields = [];
    private DBInterface $engine;
    public MigrationBuilder $migrationBuilder;
    private bool $fieldAdded = false;
    private ?ModelEntity $entity;
    public const TIMEOUT = 3;

    abstract protected function initFields() : void;

    public function getFields() : array
    {
        return $this->fields;
    }

    public function __construct($engine)
    {
        $this->engine = $engine;
        $this->migrationBuilder = new MigrationBuilder($this->engine);
        if(!$this->fieldAdded){
            $this->initFields();
        }
        if(null === $this->entity){
            throw new ModelException("Entity is undefined !");
        }
    }

    protected function addField(Field $field) : void
    {
        $this->fields[] = $field;
    }

    protected function setEntity(ModelEntity $entity) : void
    {
        $this->entity = $entity;
    }

    public function initModel() : void
    {
        $this->migrationBuilder->setName(get_class($this));
        $this->migrationBuilder->setFields($this->fields);
        $this->migrationBuilder->build();
    }
}