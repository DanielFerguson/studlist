<?php

namespace App\Http\Controllers;

use App\Models\SteerListing;
use App\Models\StudListing;
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

        // Update the listing status (steer or stud)
        $this->updateListingFromSubscription($payload['data']['object'], 'active');

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

        // Update the listing status based on subscription status
        $subscription = $payload['data']['object'];
        $status = $this->mapStripeStatusToListingStatus($subscription['status']);
        $this->updateListingFromSubscription($subscription, $status);

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

        // Update the listing status
        $this->updateListingFromSubscription($payload['data']['object'], 'cancelled');

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
            $this->updateListingFromSubscriptionId($invoice['subscription'], 'active');
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
            $this->updateListingFromSubscriptionId($invoice['subscription'], 'cancelled');
        }

        return $result;
    }

    /**
     * Update listing status from subscription object (supports both steer and stud).
     */
    private function updateListingFromSubscription($subscription, $status = null)
    {
        $stripeSubscriptionId = $subscription['id'];
        // If status not provided, get it from subscription object
        if ($status === null && isset($subscription['status'])) {
            $status = $subscription['status'];
        }
        // Map the Stripe status to our listing status
        $listingStatus = $this->mapStripeStatusToListingStatus($status);
        $this->updateListingFromSubscriptionId($stripeSubscriptionId, $listingStatus);
    }

    /**
     * Update listing status from subscription ID (supports both steer and stud).
     */
    private function updateListingFromSubscriptionId($stripeSubscriptionId, $status)
    {
        // Try to find a steer listing with this subscription
        $this->updateSteerListingFromSubscriptionId($stripeSubscriptionId, $status);

        // Try to find a stud listing with this subscription
        $this->updateStudListingFromSubscriptionId($stripeSubscriptionId, $status);
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
     * Update stud listing status from subscription ID.
     */
    private function updateStudListingFromSubscriptionId($stripeSubscriptionId, $status)
    {
        try {
            $studListing = StudListing::where('stripe_subscription_id', $stripeSubscriptionId)->first();

            if ($studListing) {
                $studListing->update(['status' => $status]);
                Log::info('Updated stud listing status', [
                    'stud_id' => $studListing->id,
                    'subscription_id' => $stripeSubscriptionId,
                    'status' => $status,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error updating stud listing from webhook', [
                'subscription_id' => $stripeSubscriptionId,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Map Stripe subscription status to listing status.
     */
    private function mapStripeStatusToListingStatus($stripeStatus)
    {
        return match ($stripeStatus) {
            'active', 'trialing' => 'active',
            'canceled', 'incomplete_expired', 'unpaid', 'paused', 'incomplete' => 'cancelled',
            'past_due' => 'active', // Keep active but might need payment update
            default => 'draft'
        };
    }
}
