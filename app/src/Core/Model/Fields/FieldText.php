<?php

namespace App\Core\Model\Fields;

use App\Core\Model\Field;
use App\Core\Model\FieldValidate;
use App\Core\Model\ModelException;
use App\Infrastructure\DB\DBInterface;

class FieldText implements Field
{
    use FieldValidate;
    private string $name;
    private ?int $length;
    private bool $isNull;
    private ?string $value;
    private ?string $actualName = null;
    private DBInterface $engine; 
    private bool $isUniqe; 

    public function __construct(
        string $name,
        ?int $length = null,
        bool $isNull = true,
        bool $isUniqe = false
    ) {
        if ($length !== null && $length > 256) {
            throw new ModelException("Text length cannot exceed 256 characters.");
        }

        $this->name = $name;
        $this->length = $length;
        $this->isNull = $isNull;
        $this->isUniqe = $isUniqe;
    }

    public function setEngine(DBInterface $engine){
        $this->engine = $engine;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }


    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function setActualName(string $name): void
    {
        $this->actualName = $name;
    }

    public function getActualName(): ?string
    {
        return $this->actualName;
    }

    public function isNull(): bool
    {
        return $this->isNull;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function getFieldName(): string
    {
        if ($this->getLength() !== null) {
            return "TEXT(" . $this->getLength() . ")";
        } else {
            return "TEXT";
        }
    }

    public function getIsUniqe()
    {
        return $this->isUniqe;
    }

    public function setIsUniqe($isUniqe)
    {
        $this->isUniqe = $isUniqe;

        return $this;
    }
}
