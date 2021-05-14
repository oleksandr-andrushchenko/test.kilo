<?php

namespace App\Resolver;

use App\Entity\GatewayCode;
use App\Entity\SubscriptionNotification;
use App\Entity\SubscriptionNotificationType;
use App\Exception\ResolverException;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class AppleGatewaySubscriptionNotificationResolver implements GatewaySubscriptionNotificationResolverInterface
{
    public function resolveByRequest(Request $request): SubscriptionNotification
    {
        $subscriptionNotification = new SubscriptionNotification();

        $type = $this->getTypeByRequest($request);
        $subscriptionNotification->setType($type);

        $body = $this->getBodyByRequest($request);
        $subscriptionNotification->setPayload(json_encode(compact('type', 'body')));

        $receipt = $this->getLastReceiptByBody($body);
        $subscriptionNotification->setExpiresOrRenewAt($this->getExpiresOrRenewAtByReceipt($receipt));
        $subscriptionNotification->setTransactionId($this->getTransactionIdByReceipt($receipt));
        $subscriptionNotification->setProductId($this->getProductIdByReceipt($receipt));

        return $subscriptionNotification;
    }

    public function getGatewayCode(): string
    {
        return GatewayCode::APPLE;
    }

    /**
     * @param Request $request
     * @return string
     * @throws ResolverException
     */
    private function getTypeByRequest(Request $request): string
    {
        $notificationType = $request->get('notification_type');

        if (!is_int($notificationType)) {
            throw new ResolverException(
                sprintf('invalid notification type: %s', var_export($notificationType, true))
            );
        }

        switch ($notificationType) {
            case 'INITIAL_BUY':
                return SubscriptionNotificationType::BOUGHT;
            case 'DID_RENEW':
                return SubscriptionNotificationType::RENEWED;
            case 'DID_FAIL_TO_RENEW':
                return SubscriptionNotificationType::RENEWED_FAILED;
            case 'CANCEL':
                return SubscriptionNotificationType::CANCELED;
            default:
                throw new ResolverException(
                    sprintf('unknown notification type: %s, for gateway: %s', $notificationType, $this->getGatewayCode())
                );
        }
    }

    /**
     * @param Request $request
     * @return array
     * @throws ResolverException
     */
    private function getBodyByRequest(Request $request): array
    {
        $responseBody = $request->get('responseBody');

        if (!is_array($responseBody)) {
            throw new ResolverException(
                sprintf('invalid response body: %s', var_export($responseBody, true))
            );
        }

        return $responseBody;
    }

    /**
     * @param array $body
     * @return array
     * @throws ResolverException
     */
    private function getLastReceiptByBody(array $body): array
    {
        if (!isset($body['unified_receipt'], $body['unified_receipt']['latest_receipt'])) {
            throw new ResolverException(
                sprintf('invalid receipt in body: %s', var_export($body, true))
            );
        }

        return json_decode(base64_decode($body['unified_receipt']['latest_receipt']), true);
    }

    /**
     * @param array $receipt
     * @return DateTimeInterface
     * @throws ResolverException
     * @throws Exception
     */
    private function getExpiresOrRenewAtByReceipt(array $receipt): DateTimeInterface
    {
        if (!isset($receipt['expires_date'])) {
            throw new ResolverException(
                sprintf('invalid receipt expires date: %s', var_export($receipt, true))
            );
        }

        return new DateTimeImmutable($receipt['expires_date']);
    }

    /**
     * @param array $receipt
     * @return DateTimeInterface
     * @throws ResolverException
     */
    private function getTransactionIdByReceipt(array $receipt): DateTimeInterface
    {
        if (!isset($receipt['transaction_id'])) {
            throw new ResolverException(
                sprintf('invalid receipt transaction id: %s', var_export($receipt, true))
            );
        }

        return $receipt['transaction_id'];
    }

    /**
     * @param array $receipt
     * @return DateTimeInterface
     * @throws ResolverException
     */
    private function getProductIdByReceipt(array $receipt): DateTimeInterface
    {
        if (!isset($receipt['product_id'])) {
            throw new ResolverException(
                sprintf('invalid receipt product id: %s', var_export($receipt, true))
            );
        }

        return $receipt['product_id'];
    }
}