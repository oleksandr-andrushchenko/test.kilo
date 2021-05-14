<?php

namespace App\Manager;

use App\Entity\Subscription;
use App\Entity\SubscriptionNotification;
use App\Entity\SubscriptionNotificationType;
use App\Entity\SubscriptionStatus;
use App\Entity\SubscriptionTransaction;
use App\Exception\EntityException;
use App\Exception\InvalidUserException;
use App\Repository\ProductRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class SubscriptionManager
{
    private UserRepository $userRepository;
    private ProductRepository $productRepository;
    private SubscriptionRepository $subscriptionRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserRepository $userRepository,
        ProductRepository $productRepository,
        SubscriptionRepository $subscriptionRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param SubscriptionNotification $subscriptionNotification
     * @throws InvalidUserException
     * @throws EntityException
     */
    public function updateSubscription(
        SubscriptionNotification $subscriptionNotification
    ) {
        $this->entityManager->persist($subscriptionNotification);

        $subscription = $this->getSubscriptionByNotification($subscriptionNotification);

        switch ($subscriptionNotification->getType()) {
            case SubscriptionNotificationType::BOUGHT:
            case SubscriptionNotificationType::RENEWED:
                $this->createTransactionByNotification($subscriptionNotification);

                $subscription->setStatus(SubscriptionStatus::ACTIVE)
                    ->setExpiredAt($subscriptionNotification->getExpiresOrRenewAt());
                break;
            case SubscriptionNotificationType::CANCELED:
            case SubscriptionNotificationType::RENEWED_FAILED:
            default:
                $subscription->setStatus(SubscriptionStatus::INACTIVE);
        }
    }

    private function createTransactionByNotification(
        SubscriptionNotification $subscriptionNotification
    ) {
        $transaction = new SubscriptionTransaction(
            $subscriptionNotification->getSubscription(),
            $subscriptionNotification->getSum(),
        );
        $this->entityManager->persist($transaction);

        return $transaction;
    }

    /**
     * @param SubscriptionNotification $subscriptionNotification
     * @return Subscription|object
     * @throws EntityException
     * @throws InvalidUserException
     */
    private function getSubscriptionByNotification(
        SubscriptionNotification $subscriptionNotification
    ) {
        $user = $this->userRepository->findOneByGatewayUserId(
            $subscriptionNotification->getGateway(),
            $subscriptionNotification->getUserId()
        );

        if ($user === null) {
            throw new InvalidUserException(
                sprintf(
                    'unknown gateway user with gateway: %s and id: %s',
                    $subscriptionNotification->getGateway()->getCode(),
                    $subscriptionNotification->getUserId()
                )
            );
        }

        $subscriptionNotification->setUser($user);

        $product = $this->productRepository->findOneByGatewayProductId(
            $subscriptionNotification->getGateway(),
            $subscriptionNotification->getProductId()
        );

        if ($product === null) {
            throw new InvalidUserException(
                sprintf(
                    'unknown gateway product with gateway: %s and id: %s',
                    $subscriptionNotification->getGateway()->getCode(),
                    $subscriptionNotification->getProductId()
                )
            );
        }

        $subscriptionNotification->setProduct($product);

        $subscription = $this->subscriptionRepository->findOneBySubscriptionNotification($subscriptionNotification);

        if ($subscription === null) {
            $subscription = new Subscription($product, $user, $subscriptionNotification->getGateway());
            $this->entityManager->persist($subscription);
        }

        $subscriptionNotification->setSubscription($subscription);

        return $subscription;
    }
}
