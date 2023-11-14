<?php

namespace App\Core\Model;
use App\Infrastructure\DB\Connect;
use App\Infrastructure\DB\DBInterface;
use ReflectionClass;
use TypeError;

abstract class AbstractModel
{
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
            throw new ModelException("entity is udefined");
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
        $count = 0;
        foreach ($this->fields as $field){
            if($count !== 0 && count($fieldsOrder)>0 ){
                $sql.=',';
            }
            $sql.=$this->engine->escapeString($field->getName());
            $count++;
        }
        $sql.=') VALUES (';

        $count = 0;
        foreach ($fieldsOrder as $field){
            if( $count !== 0 && count($fieldsOrder)>0 ){
                $sql.=',';
            }
            $sql.='"'.$this->engine->escapeString($field).'"';
            $count++;
        }

        $sql.=');';

        $this->engine->runQuery($sql);
    }

    public function get(int $id, array $select = null) : ModelEntity
    {
        $fields = array_map(function(mixed $field){
            return $field->getName();
        }, $this->fields);
        array_unshift($fields, 'id');

        $fields = ($select === null) ? $fields : $select;

        $modelName = explode('\\',get_class($this));
        $data = $this->engine->getQueryLoop("SELECT ".implode(', ',$fields)." FROM `".$this->engine->escapeString(end($modelName))."` Where id = ".intval($id));

        if(0 === count($data)){
            throw new ModelException("Data Not found");
        }

        $data = $data[0];

        $reflectionEntity = new ReflectionClass($this->entity);

        foreach ($reflectionEntity->getProperties() as $entity){
            if(in_array($entity->name,$fields)) {
                $method = 'set' . ucfirst($entity->name);
                $this->entity->$method($data[$entity->name]);
            }
        }

        return $this->entity;
    }

    public function getAll(int $onPage = null,array $select = null) : array
    {
        $fields = array_map(function(mixed $field){
            return $field->getName();
        }, $this->fields);
        array_unshift($fields, 'id');

        $fields = ($select === null) ? $fields : $select;

        $modelName = explode('\\',get_class($this));

        $sql = "SELECT ".implode(', ',$fields)." FROM `".$this->engine->escapeString(end($modelName))."`";

        try{
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $paginateStart = ($page - 1) * $onPage;

            if($onPage){
                $sql.= " LIMIT ".$paginateStart.", ".$onPage."";
            }
        }
        catch (TypeError $e){
            throw new ModelException("Page must be Int !");
        }

        $records = [];

        foreach ($this->engine->getQueryLoop($sql) as $recordsData) {
            foreach ($recordsData as $key => $field){
                if (in_array($key, $fields)) {
                    $method = 'set' . ucfirst($key);
                    $this->entity->$method($field);
                }
            }
            $records[] = $this->entity;

        }

        return $records;
    }

    private function getFields(ModelEntity $entity) : array
    {
        $reflectionEntity = new ReflectionClass($entity);
        $this->entity = $entity;
        $fields = [];

        foreach ($reflectionEntity->getProperties() as $field){
            if($field->name !== 'id') {
                $this->findField($this->fields, $field->name)->validate((empty($field)) ? null : $field);
                $field->setAccessible(true);
                $fields[$field->getName()] = $field->getValue($entity);
            }
        }

        return $fields;
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

    public function add(ModelEntity $entity) : void
    {
        $this->insert($this->getFields($entity));
    }

    public function change(ModelEntity $entity,?int $id) : void
    {
        $this->update($this->getFields($entity),$id);
    }
}