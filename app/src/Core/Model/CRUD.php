<?php

namespace App\Core\Model;

use TypeError;
use ReflectionClass;
use App\Core\Model\QueryBuilder\Query;
use App\Infrastructure\DB\DBInterface;

trait CRUD
{
    private DBInterface $engine;

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
            $method = 'set' . ucfirst($entity->name);
            $this->entity->$method($data[$entity->name]);
        }
    }

    private function prepareFieldsForSelect(?array $select): array
    {
        $fields = array_map(fn($field) => $field->getName(), $this->fields);
        array_unshift($fields, 'id');

        return $select ?? $fields;
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

    private function getField(ModelEntity $entity) : array
    {
        $reflectionEntity = new ReflectionClass($entity);
        $this->entity = $entity;
        $fields = [];

        foreach ($reflectionEntity->getProperties() as $field){
            if($field->name !== 'id') {
                $fieldObj = $this->findField($this->fields, $field->name);
                $fieldObj->setEngine($this->engine);

                $field->setAccessible(true);
                $fields[$field->getName()] = $field->getValue($entity);
            
                $value = (empty($field->getValue($entity))) ? null : $field->getValue($entity);

                $fieldObj->validate($value);
            }
        }

        return $fields;
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

        return $this->fetchRecordsWithQuery($sql);
    }

    public function getAll(int $onPage = null,array $select = null) : array
    {
        $fields = $this->prepareFieldsForSelect($select);

        $tableName = $this->getTableName();

        $sql = "SELECT " . implode(', ', $fields) . " FROM `$tableName`";

        $this->addPaginationToQuery($sql, $onPage);

        return $this->fetchRecordsWithQuery($sql);
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

    private function timeOutInsert(string $insertQuery,string $currentTimestamp) : void 
    {
        $this->engine->runQuery($insertQuery);
        $this->engine->runQuery('INSERT INTO `Register` (`time`) VALUES ('.$currentTimestamp.');');
    }

    private function setTimeOut(string $insertQuery) : void
    {
        $query = "SELECT time FROM Register ORDER BY time DESC LIMIT 1";
        $query = $this->engine->getQueryLoop($query);
        $currentTimestamp = time();

        if (count($query) > 0) {

            $delayInSeconds = self::TIMEOUT;
            $lastTimestamp = $query[0]['time'];

            if (!($currentTimestamp - $lastTimestamp < $delayInSeconds)){
                $this->timeOutInsert($insertQuery,$currentTimestamp);
            }

        }else{
            $this->timeOutInsert($insertQuery,$currentTimestamp);
        }
    }

    public function add(ModelEntity $entity): void
    {
        $fields = $this->getField($entity);
        $insertQuery = $this->buildInsertQuery($fields);
        $this->setTimeOut($insertQuery);
    }

    public function change(ModelEntity $entity,?int $id) : void
    {
        $this->update($this->getField($entity),$id);
    }

    public function count(array $params) : int
    {
        $modelName = explode('\\',get_class($this));
        return $this->engine->countSQL(end($modelName),$params);
    }
}