<?php

namespace App\Infrastructure\DB\Engines;

use App\Infrastructure\DB\DBInterface;
use App\Infrastructure\DB\DDException;
use App\Settings\DBSettings;
use PDO;

class PDOEngin  implements DBInterface
{
    private PDO $pdo;

    public function __construct(){
        try {
            $this->pdo = new PDO("mysql:host=".DBSettings::HOST.";dbname=".DBSettings::DBNAME, DBSettings::USERNAME, DBSettings::PASSWORD);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch (\PDOException $exception){
            throw new DDException($exception->getMessage());
        }
    }

    public function getQueryLoop(string $sql, $array = []): array
    {
        try {
            $query=$this->pdo->prepare($sql);
            $query->execute($array);
            $records = [];
            while($row = $query->fetch()){
                $records[]=$row;
            }
            return $records;
        }catch (\PDOException $exception){
            throw new DDException($exception->getMessage());
        }
    }

    public function runQuery(string $sql, string $message, $array = []): string
    {
        try {
            $query=$this->pdo->prepare($sql);
            $query=$query->execute($array);
            if ($query){
                return $message;
            }
        }catch (\PDOException $exception){
            throw new DDException($exception->getMessage());
        }
    }
}