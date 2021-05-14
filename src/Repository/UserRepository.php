<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Gateway;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param Gateway $gateway
     * @param string $userId
     * @return User|null|object
     */
    public function findOneByGatewayUserId(
        Gateway $gateway,
        string $userId
    ): ?User {
        return $this->findOneBy([
            $gateway->getCode() . 'Id' => $userId,
        ]);
    }
}
