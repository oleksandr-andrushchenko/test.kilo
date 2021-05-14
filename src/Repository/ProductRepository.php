<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Gateway;
use App\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    /**
     * @param Gateway $gateway
     * @param string $productId
     * @return Product|null|object
     */
    public function findOneByGatewayProductId(
        Gateway $gateway,
        string $productId
    ): ?Product {
        return $this->findOneBy([
            $gateway->getCode() . 'Id' => $productId,
        ]);
    }
}
