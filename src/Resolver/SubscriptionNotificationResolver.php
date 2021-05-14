<?php

namespace App\Resolver;

use App\Entity\Gateway;
use App\Entity\SubscriptionNotification;
use App\Exception\ResolverException;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionNotificationResolver
{
    /**
     * @var GatewaySubscriptionNotificationResolverInterface[]
     */
    private array $gatewaySubscriptionNotificationResolvers;

    public function __construct(
        array $gatewaySubscriptionNotificationResolvers = []
    ) {
        $this->gatewaySubscriptionNotificationResolvers = $gatewaySubscriptionNotificationResolvers;
    }

    public function addGatewaySubscriptionNotificationResolver(
        GatewaySubscriptionNotificationResolverInterface $gatewaySubscriptionNotificationResolver
    ): self {
        $this->gatewaySubscriptionNotificationResolvers[] = $gatewaySubscriptionNotificationResolver;

        return $this;
    }

    /**
     * @param Gateway $gateway
     * @param Request $request
     * @return SubscriptionNotification
     * @throws ResolverException
     */
    public function resolveSubscriptionNotificationByGatewayAndRequest(Gateway $gateway, Request $request): SubscriptionNotification
    {
        foreach ($this->gatewaySubscriptionNotificationResolvers as $gatewaySubscriptionNotificationResolver) {
            if ($gateway->getCode() === $gatewaySubscriptionNotificationResolver->getGatewayCode()) {
                $subscriptionNotification = $gatewaySubscriptionNotificationResolver->resolveByRequest($request);
                $subscriptionNotification->setGateway($gateway);

                return $subscriptionNotification;
            }
        }

        throw new ResolverException(
            sprintf('invalid resolver for gateway: %s', $gateway->getCode())
        );
    }
}
