<?php

namespace App\Infrastructure\DB;

use App\Infrastructure\DB\Engines\MysqliEngin;
use App\Infrastructure\DB\Engines\PDOEngin;
use App\Settings\DBSettings;

class Connect
{
    private ?DBInterface $engin;
    private array $engins = ['PDO','MYSQLI'];

    private function __construct()
    {
        $this->engin=$this->setEngin(DBSettings::ENGINE);
    }

    private function setEngin(string $engin) :?DBInterface
    {
        if (!in_array($engin,$this->engins)){
            throw new DDException('Invalid Engin !');
        }

        switch($engin) {
            case 'PDO':
                return new PDOEngin();
            case 'MYSQLI':
                return new MysqliEngin();
        }

        return null;
    }

    public function getEngin() : ?DBInterface
    {
        return $this->engin;
    }

    public static function getInstance()
    {
        return new static();
    }



}