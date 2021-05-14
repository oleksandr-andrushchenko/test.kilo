<?php

namespace App\Entity;

class SubscriptionStatus
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';
    public const TRIAL = 'trial';

    public const ALL = [
        self::ACTIVE,
        self::INACTIVE,
        self::TRIAL,
    ];
}