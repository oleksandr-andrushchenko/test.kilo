<?php

namespace App\Resolver;

use App\Entity\Gateway;
use App\Exception\ResolverException;
use App\Repository\GatewayRepository;

class GatewayResolver
{
    private GatewayRepository $gatewayRepository;

    public function __construct(
        GatewayRepository $gatewayRepository
    ) {
        $this->gatewayRepository = $gatewayRepository;
    }

    /**
     * @param string $gatewayCode
     * @return Gateway
     * @throws ResolverException
     */
    public function resolveActiveGatewayByCode(string $gatewayCode): Gateway
    {
        $gateway = $this->gatewayRepository->findOneByCode($gatewayCode);

        if ($gateway === null) {
            throw new ResolverException(
                sprintf('invalid gateway: %s', $gatewayCode)
            );
        }

        if (!$gateway->isActive()) {
            throw new ResolverException(
                sprintf('inactive gateway: %s', $gatewayCode)
            );
        }

        return $gateway;
    }
}
