<?php

namespace Tests\Traits;

use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Subscription;

trait MocksStripeApi
{
    /**
     * Mock a successful Stripe checkout session
     */
    protected function mockStripeCheckoutSession(array $attributes = []): Session
    {
        $session = $this->createMock(Session::class);

        $defaultAttributes = [
            'id' => 'cs_test_'.uniqid(),
            'payment_status' => 'paid',
            'subscription' => 'sub_test_'.uniqid(),
            'customer' => 'cus_test_'.uniqid(),
        ];

        $attributes = array_merge($defaultAttributes, $attributes);

        foreach ($attributes as $property => $value) {
            $session->{$property} = $value;
        }

        return $session;
    }

    /**
     * Mock a Stripe subscription object
     */
    protected function mockStripeSubscription(array $attributes = []): Subscription
    {
        $subscription = $this->createMock(Subscription::class);

        $defaultAttributes = [
            'id' => 'sub_test_'.uniqid(),
            'status' => 'active',
            'customer' => 'cus_test_'.uniqid(),
            'items' => (object) [
                'data' => [
                    (object) [
                        'id' => 'si_test_'.uniqid(),
                        'price' => (object) [
                            'id' => 'price_test_'.uniqid(),
                            'product' => 'prod_test_'.uniqid(),
                        ],
                        'quantity' => 1,
                    ],
                ],
            ],
        ];

        $attributes = array_merge($defaultAttributes, $attributes);

        foreach ($attributes as $property => $value) {
            $subscription->{$property} = $value;
        }

        return $subscription;
    }

    /**
     * Mock Stripe customer creation
     */
    protected function mockStripeCustomerCreation(?string $customerId = null): Customer
    {
        $customer = $this->createMock(Customer::class);
        $customer->id = $customerId ?? 'cus_test_'.uniqid();

        return $customer;
    }

    /**
     * Mock a Stripe API failure
     */
    protected function mockStripeApiFailure(string $message = 'API request failed'): ApiErrorException
    {
        return new ApiErrorException($message);
    }

    /**
     * Set up Stripe test environment
     */
    protected function setUpStripeTest(): void
    {
        config([
            'cashier.key' => 'pk_test_fake',
            'cashier.secret' => 'sk_test_fake',
            'cashier.webhook.secret' => 'whsec_test_fake',
            'cashier.price_ids.steer_listing' => 'price_test_steer',
            'cashier.price_ids.stud_listing' => 'price_test_stud',
        ]);
    }

    /**
     * Mock Stripe static method calls for testing
     */
    protected function mockStripeStaticCalls(): void
    {
        // Since Stripe uses static methods, we'll use test mode checks
        // in the actual controllers to bypass Stripe calls during testing
        $this->app->bind(\Stripe\StripeClient::class, function () {
            $client = $this->createMock(\Stripe\StripeClient::class);

            // Mock checkout sessions
            $checkoutSessions = $this->createMock(\Stripe\Service\Checkout\SessionService::class);
            $client->checkout = (object) ['sessions' => $checkoutSessions];

            // Mock subscriptions
            $subscriptions = $this->createMock(\Stripe\Service\SubscriptionService::class);
            $client->subscriptions = $subscriptions;

            return $client;
        });
    }
}
