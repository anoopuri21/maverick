<?php

namespace App\Payments;

class PaymentGatewayManager
{
    public function gateway(?string $name = null): PaymentGateway
    {
        $name = $name ?: (string) config('payments.gateway', 'stripe');
        $config = (array) config("payments.gateways.{$name}", []);

        return match ($name) {
            'stripe' => new StripeGateway($config),
            default => throw new \InvalidArgumentException("Unsupported payment gateway: {$name}"),
        };
    }

    public function isReady(?string $name = null): bool
    {
        return $this->gateway($name)->isConfigured();
    }
}
