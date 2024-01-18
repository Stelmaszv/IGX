<?php

namespace App\Core\Model\Fields;

use App\Core\Model\Field;
use App\Core\Model\ModelException;
use App\Infrastructure\DB\DBInterface;
use App\Core\Model\ModelValidateException;

class SelectValue implements Field
{
    private string $name;
    private ?string $actualName = null;
    private ?string $value;
    private int $length;
    private bool $isNull;
    private array $options;
    protected DBInterface $engine; 

    public function __construct(string $name, int $length, array $options, bool $isNull = true)
    {
        if ($length > 256) {
            throw new ModelException("Varchar length cannot exceed 256 characters.");
        }

        $this->name = $name;
        $this->options = $options;
        $this->length = $length;
        $this->isNull = $isNull;
    }

    public function setEngine(DBInterface $engine){
        $this->engine = $engine;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public function setActualName(string $name): void
    {
        $this->actualName = $name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
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

    public function getLength(): int
    {
        return $this->length;
    }

    public function getFieldName(): string
    {
        return "VARCHAR({$this->getLength()})";
    }
 
    public function validate(mixed $value): void
    { 
        if (!in_array($value, $this->options)) {
            throw new ModelValidateException('Invalid value for select !');
        }

        if(!$this->isNull() && $value === null){
            throw new ModelValidateException('This field cannot not be null !');
        }

        if($value !== null){
            if (strlen($value) > $this->getLength()){
                throw new ModelValidateException('This value '.strlen($value).' is to length max length '.$this->getLength().'!');
            }
        }
    }
}
