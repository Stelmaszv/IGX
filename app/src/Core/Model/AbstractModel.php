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

    abstract protected function initFields() : void;

    public function __construct()
    {
        $connect = Connect::getInstance();
        $this->engine = $connect->getEngine();
        $this->migrationBuilder = new MigrationBuilder($this->engine);
        if(!$this->fieldAdded){
            $this->initFields();
        }
        if(null === $this->entity){
            throw new ModelException("entity is undefined");
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