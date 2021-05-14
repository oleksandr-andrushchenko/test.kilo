<?php

namespace App\Entity;

use DateTimeInterface;

class SubscriptionNotification
{
    private ?int $id;
    private ?Gateway $gateway;
    private ?string $type;
    private ?DateTimeInterface $expiresOrRenewAt;
    private ?string $payload;
    private ?string $transactionId;
    private ?string $productId;
    private ?string $userId;
    private ?User $user;
    private ?Product $product;
    private ?Subscription $subscription;
    private ?Money $sum;

    public function __construct()
    {
        $this->id = null;
        $this->type = null;
        $this->expiresOrRenewAt = null;
        $this->payload = null;
        $this->gateway = null;
        $this->transactionId = null;
        $this->userId = null;
        $this->productId = null;
        $this->user = null;
        $this->product = null;
        $this->subscription = null;
        $this->sum = null;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setGateway(Gateway $gateway): self
    {
        $this->gateway = $gateway;

        return $this;
    }

    public function getGateway(): ?Gateway
    {
        return $this->gateway;
    }

    public function setPayload(string $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function setExpiresOrRenewAt(DateTimeInterface $expiresOrRenewAt): self
    {
        $this->expiresOrRenewAt = $expiresOrRenewAt;

        return $this;
    }

    public function getExpiresOrRenewAt(): ?DateTimeInterface
    {
        return $this->expiresOrRenewAt;
    }

    public function setTransactionId(string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function setProductId(string $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function setSubscription(Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }

    public function getSum(): ?Money
    {
        return $this->sum;
    }
}