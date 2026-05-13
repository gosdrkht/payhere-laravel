<?php

namespace LeadingEdge\PayHere;

class PayHere
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Generate PayHere checkout URL
     */
    public function getCheckoutUrl(): string
    {
        return $this->config['sandbox']
            ? $this->config['sandbox_url']
            : $this->config['live_url'];
    }

    /**
     * Generate payment hash
     */
    public function generateHash(string $orderId, float $amount): string
    {
        $merchantId     = $this->config['merchant_id'];
        $merchantSecret = $this->config['merchant_secret'];
        $currency       = $this->config['currency'];

        $hashedSecret = strtoupper(md5($merchantSecret));
        $amountFormatted = number_format($amount, 2, '.', '');

        return strtoupper(md5(
            $merchantId . $orderId . $amountFormatted . $currency . $hashedSecret
        ));
    }

    /**
     * Build checkout form data array
     */
    public function buildCheckout(array $params): array
    {
        $hash = $this->generateHash($params['order_id'], $params['amount']);

        return [
            'merchant_id'  => $this->config['merchant_id'],
            'return_url'   => $params['return_url'],
            'cancel_url'   => $params['cancel_url'],
            'notify_url'   => $params['notify_url'],
            'order_id'     => $params['order_id'],
            'items'        => $params['items'],
            'currency'     => $this->config['currency'],
            'amount'       => number_format($params['amount'], 2, '.', ''),
            'first_name'   => $params['first_name'],
            'last_name'    => $params['last_name'] ?? '',
            'email'        => $params['email'],
            'phone'        => $params['phone'],
            'address'      => $params['address'] ?? '',
            'city'         => $params['city'] ?? 'Colombo',
            'country'      => $params['country'] ?? 'Sri Lanka',
            'hash'         => $hash,
        ];
    }

    /**
     * Verify PayHere notification (webhook)
     */
    public function verifyNotification(array $data): bool
    {
        $merchantId     = $this->config['merchant_id'];
        $merchantSecret = $this->config['merchant_secret'];

        $hashedSecret = strtoupper(md5($merchantSecret));

        $localHash = strtoupper(md5(
            $merchantId .
            $data['order_id'] .
            $data['payhere_amount'] .
            $data['payhere_currency'] .
            $data['status_code'] .
            $hashedSecret
        ));

        return $localHash === $data['md5sig'];
    }
}