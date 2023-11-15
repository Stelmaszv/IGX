<?php

namespace App\Core\Model;

use App\Core\Model\QueryBuilder\Query;
use App\Infrastructure\DB\DBInterface;
use ReflectionClass;
use TypeError;

trait CRUD
{
    private function getTableName(): string
    {
        $modelName = explode('\\', get_class($this));
        return $this->engine->escapeString(end($modelName));
    }

    private function findField(array $data, string $value): ?Field
    {
        $filteredFields = array_filter($data, fn($sqlField) => $sqlField->getName() === $value);

        return $filteredFields ? reset($filteredFields) : null;
    }

    private function buildInsertQuery(array $fieldsOrder): string
    {
        $tableName = $this->getTableName();
        $columns = array_map(fn($field) => $this->engine->escapeString($field->getName()), $this->fields);
        $values = array_map(fn($field) => '"' . $this->engine->escapeString($field) . '"', $fieldsOrder);

        return sprintf(
            'INSERT INTO `%s` (%s) VALUES (%s);',
            $tableName,
            implode(', ', $columns),
            implode(', ', $values)
        );
    }

    private function validateDataFound(array $data): void
    {
        if (count($data) === 0) {
            throw new ModelException("Data not found");
        }
    }

    private function hydrateEntityProperties(array $data): void
    {
        $reflectionEntity = new ReflectionClass($this->entity);

        foreach ($reflectionEntity->getProperties() as $entity) {
            if (in_array($entity->name, $data)) {
                $method = 'set' . ucfirst($entity->name);
                $this->entity->$method($data[$entity->name]);
            }
        }
    }

    private function prepareFieldsForSelect(?array $select): array
    {
        $fields = array_map(fn($field) => $field->getName(), $this->fields);
        array_unshift($fields, 'id');

        return $select ?? $fields;
    }

    public function get(int $id, array $select = null) : ModelEntity
    {
        $fields = $this->prepareFieldsForSelect($select);
        $tableName = $this->getTableName();

        $query = sprintf(
            "SELECT %s FROM `%s` WHERE id = %d",
            implode(', ', $fields),
            $tableName,
            intval($id)
        );

        $data = $this->engine->getQueryLoop($query);

        $this->validateDataFound($data);

        $data = $data[0];
        $this->hydrateEntityProperties($data);

        return $this->entity;
    }

    public function getFiltered(Query $sqlData = null, int $onPage = null, array $select = null): array
    {
        if ($sqlData === null) {
            return $this->getAll($onPage, $select);
        }

        $fields = $this->prepareFieldsForSelect($select);
        $tableName = $this->getTableName();

        $sql = "SELECT " . implode(', ', $fields) . " FROM `$tableName`";

        $this->addPaginationToQuery($sql, $onPage);

        if ($sqlData !== null) {
            $sqlData->setQueryArray();
            $sql .= $sqlData->getSql();
        }

        $records = $this->fetchRecordsWithQuery($sql);

        return $records;
    }

    private function addPaginationToQuery(string &$sql, ?int $onPage): void
    {
        try {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $paginateStart = ($page - 1) * $onPage;

            if ($onPage) {
                $sql .= " LIMIT $paginateStart, $onPage";
            }
        } catch (TypeError $e) {
            throw new ModelException("Page must be an integer!");
        }
    }

    private function fetchRecordsWithQuery(string $sql): array
    {
        $records = [];

        foreach ($this->engine->getQueryLoop($sql) as $recordsData) {
            $this->hydrateEntityProperties($recordsData);
            $records[] = clone $this->entity; // Clone the entity to avoid overwriting the same instance
        }

        return $records;
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

    public function add(ModelEntity $entity): void
    {
        $fields = $this->getFields($entity);
        $insertQuery = $this->buildInsertQuery($fields);
        $this->engine->runQuery($insertQuery);
    }

    public function change(ModelEntity $entity,?int $id) : void
    {
        $this->update($this->getFields($entity),$id);
    }
}