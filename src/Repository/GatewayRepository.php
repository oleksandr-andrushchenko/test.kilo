<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Gateway;
use Doctrine\ORM\EntityRepository;

class GatewayRepository extends EntityRepository
{
    public function findOneByCode(string $code): ?Gateway
    {
        return $this->findOneBy([
            'code' => $code,
        ]);
    }
}
