<?php

namespace App\Entity;

class SubscriptionTransaction
{
    private ?int $id;
    private Subscription $subscription;
    private string $sumAmount;
    private string $sumCurrency;

    public function __construct(
        Subscription $subscription,
        Money $sum
    ) {
        $this->id = null;
        $this->subscription = $subscription;
        $this->setSum($sum);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }

    public function setSum(Money $sum): self
    {
        $this->sumAmount = $sum->getAmount();
        $this->sumCurrency = $sum->getCurrency();

        return $this;
    }

    public function getSum(): Money
    {
        return Money::create($this->sumAmount, $this->sumCurrency);
    }
}