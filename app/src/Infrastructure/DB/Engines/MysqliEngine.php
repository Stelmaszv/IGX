<?php

namespace App\Infrastructure\DB\Engines;

use App\Infrastructure\DB\DBInterface;
use App\Infrastructure\DB\DBException;
use App\Settings\DBSettings;
use Exception;

class MysqliEngine implements DBInterface
{
    private \MySQLi $com;

    public function __construct()
    {
        try {
            $this->com = new \MySQLi(DBSettings::HOST, DBSettings::USERNAME, DBSettings::PASSWORD, DBSettings::DBNAME);
        } catch (\mysqli_sql_exception $exception) {
            throw new DBException($exception->getMessage());
        }
    }

    public function getQueryLoop(string $sql, $array = []): array
    {
        try {
            $records = [];
            $result = mysqli_query($this->com, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $records[] = $row;
            }
            return $records;
        } catch (\mysqli_sql_exception $exception) {
            throw new DBException($exception->getMessage());
        }
    }

    public function getLostId() : int
    {
        return $this->com->insert_id;
    }

    public function escapeString(string $word): string
    {
        return mysqli_real_escape_string($this->com, $word);
    }

    public function countSQL(string $table, array $params): int
    {
        $sql = "SELECT COUNT(*) FROM  " . $this->escapeString($table);
        $paramEl = 0;

        if (!empty($params)) {
            $sql .= ' WHERE ';
            foreach ($params as $param) {
                if($paramEl > 0){
                    $sql .= ' AND ';
                }
                $sql .= $this->escapeString($param['column']) . ' = "' . $this->escapeString($param['value']) . '"';
                $paramEl++;
            }
        }

        return (int)$this->getQueryLoop($sql)[0]['COUNT(*)'];
    }

    public function runQuery(string $sql, string $message = '', $array = []): string
    {
        try {
            $query = mysqli_query($this->com, $sql);
            if ($query) {
                return $message;
            } else {
                return 'error in query : ' . $sql;
            }
        } catch (\mysqli_sql_exception $exception) {
            throw new DBException($exception->getMessage());
        }
    }
}