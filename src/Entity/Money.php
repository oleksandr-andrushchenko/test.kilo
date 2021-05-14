<?php

namespace App\Entity;

class Money
{
    private string $amount;
    private string $currency;

    public function __construct(
        string $amount,
        string $currency
    ) {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public static function create(
        string $amount,
        string $currency
    ): self {
        return new self($amount, $currency);
    }
}