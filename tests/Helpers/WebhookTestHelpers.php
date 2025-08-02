<?php

namespace Tests\Helpers;

use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Event;
use Stripe\Webhook;

trait WebhookTestHelpers
{
    /**
     * Create a mock Stripe webhook event
     */
    protected function createWebhookEvent(string $type, array $data = []): array
    {
        return [
            'id' => 'evt_' . uniqid(),
            'object' => 'event',
            'type' => $type,
            'created' => time(),
            'data' => [
                'object' => $data,
            ],
            'livemode' => false,
            'pending_webhooks' => 1,
            'request' => [
                'id' => null,
                'idempotency_key' => null,
            ],
        ];
    }

    /**
     * Create a webhook request with valid signature
     */
    protected function createWebhookRequest(array $event): Request
    {
        $payload = json_encode($event);
        $timestamp = time();
        $secret = config('cashier.webhook.secret');
        
        // Generate signature
        $signedPayload = "{$timestamp}.{$payload}";
        $signature = hash_hmac('sha256', $signedPayload, $secret);
        
        $request = Request::create('/stripe/webhook', 'POST', [], [], [], [], $payload);
        $request->headers->set('Stripe-Signature', "t={$timestamp},v1={$signature}");
        
        return $request;
    }

    /**
     * Test a webhook handler method using reflection
     */
    protected function testWebhookHandler(string $method, array $data, ?string $status = null): void
    {
        $controller = new WebhookController();
        $reflection = new \ReflectionClass($controller);
        $handlerMethod = $reflection->getMethod($method);
        $handlerMethod->setAccessible(true);
        
        if ($status !== null) {
            $handlerMethod->invoke($controller, $data, $status);
        } else {
            $handlerMethod->invoke($controller, $data);
        }
    }

    /**
     * Create subscription webhook data
     */
    protected function createSubscriptionWebhookData(array $attributes = []): array
    {
        $defaults = [
            'id' => 'sub_' . uniqid(),
            'object' => 'subscription',
            'status' => 'active',
            'customer' => 'cus_' . uniqid(),
            'items' => [
                'object' => 'list',
                'data' => [
                    [
                        'id' => 'si_' . uniqid(),
                        'object' => 'subscription_item',
                        'price' => [
                            'id' => 'price_' . uniqid(),
                            'object' => 'price',
                            'product' => 'prod_' . uniqid(),
                        ],
                        'quantity' => 1,
                    ]
                ],
            ],
            'created' => time(),
            'current_period_start' => time(),
            'current_period_end' => time() + 2592000, // 30 days
        ];
        
        return array_merge($defaults, $attributes);
    }

    /**
     * Create checkout session webhook data
     */
    protected function createCheckoutSessionWebhookData(array $attributes = []): array
    {
        $defaults = [
            'id' => 'cs_' . uniqid(),
            'object' => 'checkout.session',
            'payment_status' => 'paid',
            'status' => 'complete',
            'subscription' => 'sub_' . uniqid(),
            'customer' => 'cus_' . uniqid(),
            'mode' => 'subscription',
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
        ];
        
        return array_merge($defaults, $attributes);
    }

    /**
     * Assert webhook was logged
     */
    protected function assertWebhookLogged(string $type, array $expectedContext = []): void
    {
        Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message, $context) use ($type, $expectedContext) {
                if (!str_contains($message, $type)) {
                    return false;
                }
                
                foreach ($expectedContext as $key => $value) {
                    if (!isset($context[$key]) || $context[$key] !== $value) {
                        return false;
                    }
                }
                
                return true;
            });
    }

    /**
     * Assert webhook error was logged
     */
    protected function assertWebhookErrorLogged(string $errorMessage, array $expectedContext = []): void
    {
        Log::shouldReceive('error')
            ->once()
            ->withArgs(function ($message, $context) use ($errorMessage, $expectedContext) {
                if (!str_contains($message, $errorMessage)) {
                    return false;
                }
                
                foreach ($expectedContext as $key => $value) {
                    if (!isset($context[$key]) || $context[$key] !== $value) {
                        return false;
                    }
                }
                
                return true;
            });
    }

    /**
     * Assert webhook warning was logged
     */
    protected function assertWebhookWarningLogged(string $warningMessage, array $expectedContext = []): void
    {
        Log::shouldReceive('warning')
            ->once()
            ->withArgs(function ($message, $context) use ($warningMessage, $expectedContext) {
                if (!str_contains($message, $warningMessage)) {
                    return false;
                }
                
                foreach ($expectedContext as $key => $value) {
                    if (!isset($context[$key]) || $context[$key] !== $value) {
                        return false;
                    }
                }
                
                return true;
            });
    }

    /**
     * Create invoice webhook data
     */
    protected function createInvoiceWebhookData(array $attributes = []): array
    {
        $defaults = [
            'id' => 'in_' . uniqid(),
            'object' => 'invoice',
            'status' => 'paid',
            'customer' => 'cus_' . uniqid(),
            'subscription' => 'sub_' . uniqid(),
            'amount_paid' => 1500,
            'amount_due' => 0,
            'currency' => 'aud',
            'paid' => true,
        ];
        
        return array_merge($defaults, $attributes);
    }

    /**
     * Create payment intent webhook data
     */
    protected function createPaymentIntentWebhookData(array $attributes = []): array
    {
        $defaults = [
            'id' => 'pi_' . uniqid(),
            'object' => 'payment_intent',
            'status' => 'succeeded',
            'amount' => 1500,
            'currency' => 'aud',
            'customer' => 'cus_' . uniqid(),
            'payment_method' => 'pm_' . uniqid(),
        ];
        
        return array_merge($defaults, $attributes);
    }
}