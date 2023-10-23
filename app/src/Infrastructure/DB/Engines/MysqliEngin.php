<?php

namespace App\Infrastructure\DB\Engines;

use App\Infrastructure\DB\DBInterface;
use App\Infrastructure\DB\DBException;
use App\Settings\DBSettings;
use Exception;

class MysqliEngin implements DBInterface
{
    private $com;
    private $sql;

    function __construct($sql = false)
    {
        try {
            $this->com = new \MySQLi(DBSettings::HOST,DBSettings::USERNAME,DBSettings::PASSWORD,DBSettings::DBNAME);
            $this->sql = $sql;
        }catch (\mysqli_sql_exception $exception){
            throw new DBException($exception->getMessage());
        }
    }

    function getQueryLoop(string $sql, $array = []): array
    {
        try {
            $records = array();
            $result = mysqli_query($this->com, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $records[] = $row;
            }
            return $records;
        }catch (\mysqli_sql_exception $exception){
            throw new DBException($exception->getMessage());
        }
    }

    public function escapeString(string $word) : string
    {
        return mysqli_real_escape_string($this->com,$word);
    }

    public function countSQl(string $table, array $parrams) : int
    {
        $sql = "SELECT COUNT(*) FROM  ".$this->escapeString($table);
        $parramEl = 0;

        if(count($parrams) > 0){
            $sql .= ' WHERE ';
            foreach ($parrams as $parram){
                if($parramEl > 0){
                    $sql .= ' AND ';
                }
                $sql .= $parram['colum'].' = "'.$parram['value'].'"';
                $parramEl ++;
            }
        }

        return $this->getQueryLoop($sql)[0]['COUNT(*)'];
    }

    function runQuery(string $sql, string $message = '', $array = []): string
    {
        try {
            $query = mysqli_query($this->com, $sql);
            if ($query) {
                return $message;
            } else {
                return 'error in query : ' . $sql;
            }
        }catch (\mysqli_sql_exception $exception){
            throw new DBException($exception->getMessage());
        }
    }

}