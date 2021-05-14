<?php

namespace App\Entity;

class User
{
    private ?int $id;
    private string $name;
    private ?string $appleId;

    public function __construct(
        string $name,
        string $appleId = null
    ) {
        $this->id = null;
        $this->name = $name;
        $this->appleId = $appleId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAppleId(): ?string
    {
        return $this->appleId;
    }

    public function setAppleId(?string $appleId): self
    {
        $this->appleId = $appleId;

        return $this;
    }
}