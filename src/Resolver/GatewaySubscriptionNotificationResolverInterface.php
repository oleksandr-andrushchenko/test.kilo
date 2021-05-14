<?php

namespace App\Resolver;

use App\Entity\SubscriptionNotification;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\ResolverException;

interface GatewaySubscriptionNotificationResolverInterface
{
    /**
     * @param Request $request
     * @return SubscriptionNotification
     * @throws ResolverException
     */
    public function resolveByRequest(Request $request): SubscriptionNotification;

    public function getGatewayCode(): string;
}
