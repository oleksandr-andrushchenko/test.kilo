<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Subscription;
use App\Entity\SubscriptionNotification;
use Doctrine\ORM\EntityRepository;

class SubscriptionRepository extends EntityRepository
{
    /**
     * @param SubscriptionNotification $subscriptionNotification
     * @return Subscription|null|object
     */
    public function findOneBySubscriptionNotification(
        SubscriptionNotification $subscriptionNotification
    ): ?Subscription {
        return $this->findOneBy([
            'product' => $subscriptionNotification->getProduct(),
            'user' => $subscriptionNotification->getUser(),
            'gateway' => $subscriptionNotification->getGateway(),
        ]);
    }
}
