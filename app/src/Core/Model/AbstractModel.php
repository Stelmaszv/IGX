<?php

namespace App\Core\Model;
use App\Infrastructure\DB\Connect;
use App\Infrastructure\DB\DBInterface;

abstract class AbstractModel
{
    private array $fields = [];
    private DBInterface $engine;
    public MigrationBuilder $migrationBuilder;

    abstract protected function initFields(): void;

    public function __construct()
    {
        $connect = Connect::getInstance();
        $this->engine = $connect->getEngine();
        $this->migrationBuilder = new MigrationBuilder($this->engine);
    }

    protected function addField(Field $field): void
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

    private function findField(array $data, string $value): ?Field
    {
        foreach ($data as $sqlField){
            if($sqlField->getName() === $value){
                return $sqlField;
            }
        }

        return null;
    }

    private function save(array $fieldsOrder) : void
    {
        $modelName = explode('\\',get_class($this));
        $sql = 'INSERT INTO `'.$this->engine->escapeString(end($modelName)).'` (';
        foreach ($this->fields as $key => $field){
            if($key !== 0){
                $sql.=',';
            }
            $sql.=$this->engine->escapeString($field->getName());
        }
        $sql.=') VALUES (';

        foreach ($fieldsOrder as $key => $field){
            if($key !== 0){
                $sql.=',';
            }
            $sql.='"'.$this->engine->escapeString($field).'"';
        }

        $sql.=');';

        $this->engine->runQuery($sql);
    }

    public function add(array $data) : void
    {
        $this->initFields();
        $fields = array_map(function(mixed $field){
            return $field->getName();
        }, $this->fields);

        foreach ($data as $key => $field){
            if(!in_array($key,$fields)){
                $modelName = explode('\\',get_class($this));
                throw new ModelException("Colum ".$field." not Exist in ".end($modelName)."!");
            }

            $this->findField($this->fields,$key)->validate((empty($field)) ? null  : $field);
        }

        $fieldsOrder = [];
        foreach ($fields as $field){
            $fieldsOrder[] = $data[$field];
        }

        $this->save($fieldsOrder);
    }
}