<?php

namespace App\Entity;

use App\Exception\EntityException;

class Gateway
{
    private ?int $id;
    private string $code;
    private bool $isActive;

    public function __construct(
        string $code,
        bool $isActive = true
    ) {
        $this->id = null;
        $this->code = $code;
        $this->isActive = $isActive;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param string $code
     * @return $this
     * @throws EntityException
     */
    public function setCode(string $code): self
    {
        if (!in_array($code, GatewayCode::ALL)) {
            throw new EntityException(
                sprintf('invalid gateway code: %s', var_export($code, true))
            );
        }

        $this->code = $code;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}