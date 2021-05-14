<?php

namespace App\Controller;

use App\Exception\ResolverException;
use App\Manager\SubscriptionManager;
use App\Resolver\GatewayResolver;
use App\Resolver\SubscriptionNotificationResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Throwable;

class SubscriptionNotificationController
{
    private Request $request;
    private GatewayResolver $gatewayResolver;
    private SubscriptionNotificationResolver $subscriptionNotificationResolver;
    private SubscriptionManager $subscriptionManager;
    private EntityManagerInterface $entityManager;

    public function __construct(
        Request $request,
        GatewayResolver $gatewayResolver,
        SubscriptionNotificationResolver $subscriptionNotificationResolver,
        SubscriptionManager $subscriptionManager,
        EntityManagerInterface $entityManager
    ) {
        $this->request = $request;
        $this->gatewayResolver = $gatewayResolver;
        $this->subscriptionNotificationResolver = $subscriptionNotificationResolver;
        $this->subscriptionManager = $subscriptionManager;
        $this->entityManager = $entityManager;
    }

    public function updateSubscription(string $gatewayCode)
    {
        try {
            $gateway = $this->gatewayResolver->resolveActiveGatewayByCode($gatewayCode);

            $subscriptionNotification = $this->subscriptionNotificationResolver
                ->resolveSubscriptionNotificationByGatewayAndRequest($gateway, $this->request);

            $this->subscriptionManager->updateSubscription($subscriptionNotification);

            $this->entityManager->flush();
        } catch (ResolverException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        } catch (Throwable $exception) {
            throw new ServiceUnavailableHttpException($exception->getMessage());
        }
    }
}