<?php

namespace App\Main\Entity;

use App\Core\Model\ModelEntity;

class CatsEntity implements ModelEntity
{
    private ?int $id;
    private ?string $name;
    private ?int $counter;
    private ?string $description;

    function __construct(string $name = null, int $counter = null, string $description = null)
    {
        $this->name = $name;
        $this->counter = $counter;
        $this->description = $description;
    }

    public function setCounter(int $counter): void
    {
        $this->counter = $counter;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCounter(): ?int
    {
        return $this->counter;
    }
}