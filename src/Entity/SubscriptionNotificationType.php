<?php

namespace App\Entity;

class SubscriptionNotificationType
{
    public const BOUGHT = 'bought';
    public const RENEWED = 'renewed';
    public const RENEWED_FAILED = 'renewed_failed';
    public const CANCELED = 'canceled';
}