<?php

namespace App\Entity;

use App\Exception\EntityException;
use DateTimeInterface;

class Subscription
{
    private ?int $id;
    private Product $product;
    private User $user;
    private Gateway $gateway;
    private string $status;
    private ?DateTimeInterface $expiredAt;

    /**
     * Subscription constructor.
     * @param Product $product
     * @param User $user
     * @param Gateway $gateway
     * @param string $status
     * @param DateTimeInterface|null $expiredAt
     * @throws EntityException
     */
    public function __construct(
        Product $product,
        User $user,
        Gateway $gateway,
        string $status = SubscriptionStatus::INACTIVE,
        DateTimeInterface $expiredAt = null
    ) {
        $this->id = null;
        $this->product = $product;
        $this->user = $user;
        $this->gateway = $gateway;
        $this->setStatus($status);
        $this->expiredAt = $expiredAt;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param string $status
     * @return $this
     * @throws EntityException
     */
    public function setStatus(string $status): self
    {
        if (!in_array($status, SubscriptionStatus::ALL)) {
            throw new EntityException(
                sprintf('invalid subscription status: %s', var_export($status, true))
            );
        }

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setExpiredAt(DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function getExpiredAt(): ?DateTimeInterface
    {
        return $this->expiredAt;
    }
}