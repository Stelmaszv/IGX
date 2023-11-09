<?php

namespace App\Core\Model;
use App\Infrastructure\DB\Connect;
use App\Infrastructure\DB\DBInterface;

abstract class AbstractModel
{
    private array $fields = [];
    private DBInterface $engine;
    public MigrationBuilder $migrationBuilder;
    private bool $fieldAdded = false;

    abstract protected function initFields(): void;

    public function __construct()
    {
        $connect = Connect::getInstance();
        $this->engine = $connect->getEngine();
        $this->migrationBuilder = new MigrationBuilder($this->engine);
        if(!$this->fieldAdded){
            $this->initFields();
        }
    }

    protected function addField(Field $field): void
    {
        $this->fields[] = $field;
    }

    public function initModel(): void
    {
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

    private function insert(array $fieldsOrder) : void
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
        var_dump($this->fields);
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

        $this->insert($fieldsOrder);
    }

    public function update(array $fields,?int $id) : void
    {
        $modelName = explode('\\',get_class($this));
        $sql = 'UPDATE `'.$this->engine->escapeString(end($modelName)).'` SET';
        $count = 0;
        foreach ($fields as $key => $field){
            if( $count !== 0 && count($fields)>0 ){
                $sql.=',';
            }
            $sql.= ' `'.$key .'` = "'.$this->engine->escapeString($field).'"';
            $count++;
        }

        if($id){
            $sql.=' WHERE `id` = '.intval($id);
        }

        $this->engine->runQuery($sql);
    }

    public function change(array $data,?int $id) : void
    {
        $fields = array_map(function(mixed $field){
            return $field->getName();
        }, $this->fields);

        foreach ($data as $key => $field){
            if(!in_array($key,$fields)){
                $modelName = explode('\\',get_class($this));
                throw new ModelException("Colum ".$key." not Exist in ".end($modelName)."!");
            }

            $this->findField($this->fields,$key)->validate((empty($field)) ? null  : $field);
        }

        $this->update($data,$id);
    }
}