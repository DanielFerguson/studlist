<?php

namespace App\Http\Controllers;

use App\Models\SteerListing;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class WebhookController extends CashierWebhookController
{
    /**
     * Handle subscription created event.
     */
    public function handleCustomerSubscriptionCreated($payload)
    {
        Log::info('Subscription created webhook', ['payload' => $payload]);

        // Let Cashier handle the basic subscription creation first
        $result = parent::handleCustomerSubscriptionCreated($payload);

        // Update the steer listing status
        $this->updateSteerListingFromSubscription($payload['data']['object'], 'active');

        return $result;
    }

    /**
     * Handle subscription updated event.
     */
    public function handleCustomerSubscriptionUpdated($payload)
    {
        Log::info('Subscription updated webhook', ['payload' => $payload]);

        // Let Cashier handle the basic subscription update first
        $result = parent::handleCustomerSubscriptionUpdated($payload);

        // Update the steer listing status based on subscription status
        $subscription = $payload['data']['object'];
        $status = $this->mapStripeStatusToSteerStatus($subscription['status']);
        $this->updateSteerListingFromSubscription($subscription, $status);

        return $result;
    }

    /**
     * Handle subscription deleted event.
     */
    public function handleCustomerSubscriptionDeleted($payload)
    {
        Log::info('Subscription deleted webhook', ['payload' => $payload]);

        // Let Cashier handle the basic subscription deletion first
        $result = parent::handleCustomerSubscriptionDeleted($payload);

        // Update the steer listing status
        $this->updateSteerListingFromSubscription($payload['data']['object'], 'cancelled');

        return $result;
    }

    /**
     * Handle invoice payment succeeded event.
     */
    public function handleInvoicePaymentSucceeded($payload)
    {
        Log::info('Invoice payment succeeded webhook', ['payload' => $payload]);

        // Let Cashier handle the basic invoice processing first
        $result = parent::handleInvoicePaymentSucceeded($payload);

        // If this is a subscription renewal, make sure the listing is active
        $invoice = $payload['data']['object'];
        if (isset($invoice['subscription'])) {
            $this->updateSteerListingFromSubscriptionId($invoice['subscription'], 'active');
        }

        return $result;
    }

    /**
     * Handle invoice payment failed event.
     */
    public function handleInvoicePaymentFailed($payload)
    {
        Log::info('Invoice payment failed webhook', ['payload' => $payload]);

        // Let Cashier handle the basic invoice processing first
        $result = parent::handleInvoicePaymentFailed($payload);

        // If this is a subscription payment failure, cancel the listing
        $invoice = $payload['data']['object'];
        if (isset($invoice['subscription'])) {
            $this->updateSteerListingFromSubscriptionId($invoice['subscription'], 'cancelled');
        }

        return $result;
    }

    /**
     * Update steer listing status from subscription object.
     */
    private function updateSteerListingFromSubscription($subscription, $status)
    {
        $stripeSubscriptionId = $subscription['id'];
        $this->updateSteerListingFromSubscriptionId($stripeSubscriptionId, $status);
    }

    /**
     * Update steer listing status from subscription ID.
     */
    private function updateSteerListingFromSubscriptionId($stripeSubscriptionId, $status)
    {
        try {
            $steerListing = SteerListing::where('stripe_subscription_id', $stripeSubscriptionId)->first();

            if ($steerListing) {
                $steerListing->update(['status' => $status]);
                Log::info('Updated steer listing status', [
                    'steer_id' => $steerListing->id,
                    'subscription_id' => $stripeSubscriptionId,
                    'status' => $status,
                ]);
            } else {
                Log::warning('Steer listing not found for subscription', [
                    'subscription_id' => $stripeSubscriptionId,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error updating steer listing from webhook', [
                'subscription_id' => $stripeSubscriptionId,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Map Stripe subscription status to steer listing status.
     */
    private function mapStripeStatusToSteerStatus($stripeStatus)
    {
        return match ($stripeStatus) {
            'active' => 'active',
            'canceled', 'incomplete_expired', 'unpaid', 'paused', 'incomplete', 'past_due' => 'cancelled',
            default => 'draft'
        };
    }
}
