<?php

namespace App\Http\Controllers;

use App\Models\SteerListing;
use App\Models\StudListing;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookHandled;
use Laravel\Cashier\Events\WebhookReceived;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Laravel\Cashier\Subscription;

class WebhookController extends CashierWebhookController
{
    /**
     * Handle subscription created event.
     */
    public function handleCustomerSubscriptionCreated($payload)
    {
        Log::info('Subscription created webhook', ['payload' => $payload]);

        $data = $payload['data']['object'];

        if ($user = $this->getUserByStripeId($data['customer'])) {
            // Check if subscription already exists
            $subscription = $user->subscriptions()->where('stripe_id', $data['id'])->first();

            if (! $subscription) {
                // Create the subscription record
                $firstItem = $data['items']['data'][0] ?? null;

                $subscription = $user->subscriptions()->create([
                    'type' => $data['metadata']['type'] ?? 'default',
                    'stripe_id' => $data['id'],
                    'stripe_status' => $data['status'],
                    'stripe_price' => $firstItem['price']['id'] ?? null,
                    'quantity' => $firstItem['quantity'] ?? 1,
                    'trial_ends_at' => isset($data['trial_end']) ? \Carbon\Carbon::createFromTimestamp($data['trial_end']) : null,
                    'ends_at' => null,
                ]);

                // Create subscription items
                foreach ($data['items']['data'] as $item) {
                    $subscription->items()->create([
                        'stripe_id' => $item['id'],
                        'stripe_product' => $item['price']['product'] ?? null,
                        'stripe_price' => $item['price']['id'],
                        'quantity' => $item['quantity'] ?? 1,
                    ]);
                }
            }
        }

        // Update the listing status (steer or stud)
        $this->updateListingFromSubscription($data, 'active');

        WebhookHandled::dispatch($payload);

        return $this->successMethod();
    }

    /**
     * Handle subscription updated event.
     */
    public function handleCustomerSubscriptionUpdated($payload)
    {
        Log::info('Subscription updated webhook', ['payload' => $payload]);

        $data = $payload['data']['object'];

        if ($user = $this->getUserByStripeId($data['customer'])) {
            $subscription = $user->subscriptions()->where('stripe_id', $data['id'])->first();

            if ($subscription) {
                $firstItem = $data['items']['data'][0] ?? null;

                // Determine ends_at based on cancellation status
                $endsAt = null;
                if ($data['cancel_at_period_end'] ?? false) {
                    $endsAt = isset($data['cancel_at'])
                        ? \Carbon\Carbon::createFromTimestamp($data['cancel_at'])
                        : (isset($firstItem['current_period_end'])
                            ? \Carbon\Carbon::createFromTimestamp($firstItem['current_period_end'])
                            : null);
                }

                $subscription->update([
                    'stripe_status' => $data['status'],
                    'stripe_price' => $firstItem['price']['id'] ?? $subscription->stripe_price,
                    'quantity' => $firstItem['quantity'] ?? $subscription->quantity,
                    'trial_ends_at' => isset($data['trial_end']) ? \Carbon\Carbon::createFromTimestamp($data['trial_end']) : $subscription->trial_ends_at,
                    'ends_at' => $endsAt,
                ]);
            }
        }

        // Update the listing status based on subscription status
        $status = $this->mapStripeStatusToListingStatus($data['status']);
        $this->updateListingFromSubscription($data, $status);

        WebhookHandled::dispatch($payload);

        return $this->successMethod();
    }

    /**
     * Handle subscription deleted event.
     */
    public function handleCustomerSubscriptionDeleted($payload)
    {
        Log::info('Subscription deleted webhook', ['payload' => $payload]);

        $data = $payload['data']['object'];

        if ($user = $this->getUserByStripeId($data['customer'])) {
            $subscription = $user->subscriptions()->where('stripe_id', $data['id'])->first();

            if ($subscription) {
                $subscription->update([
                    'stripe_status' => $data['status'],
                    'ends_at' => $subscription->ends_at ?? now(),
                ]);
            }
        }

        // Update the listing status
        $this->updateListingFromSubscription($data, 'cancelled');

        WebhookHandled::dispatch($payload);

        return $this->successMethod();
    }

    /**
     * Handle invoice payment succeeded event.
     */
    public function handleInvoicePaymentSucceeded($payload)
    {
        Log::info('Invoice payment succeeded webhook', ['payload' => $payload]);

        // Let Cashier handle the basic invoice processing
        // This event doesn't use current_period_end so it should be safe
        try {
            parent::handleInvoicePaymentSucceeded($payload);
        } catch (\Exception $e) {
            Log::warning('Cashier invoice processing failed, continuing with custom logic', [
                'error' => $e->getMessage(),
            ]);
        }

        // If this is a subscription renewal, make sure the listing is active
        $invoice = $payload['data']['object'];
        if (isset($invoice['subscription'])) {
            $this->updateListingFromSubscriptionId($invoice['subscription'], 'active');
        }

        return $this->successMethod();
    }

    /**
     * Handle invoice payment failed event.
     */
    public function handleInvoicePaymentFailed($payload)
    {
        Log::info('Invoice payment failed webhook', ['payload' => $payload]);

        // Let Cashier handle the basic invoice processing
        try {
            parent::handleInvoicePaymentFailed($payload);
        } catch (\Exception $e) {
            Log::warning('Cashier invoice processing failed, continuing with custom logic', [
                'error' => $e->getMessage(),
            ]);
        }

        // If this is a subscription payment failure, mark the listing as cancelled
        $invoice = $payload['data']['object'];
        if (isset($invoice['subscription'])) {
            $this->updateListingFromSubscriptionId($invoice['subscription'], 'cancelled');
        }

        return $this->successMethod();
    }

    /**
     * Get the billable model instance by Stripe ID.
     */
    protected function getUserByStripeId($stripeId)
    {
        return Cashier::findBillable($stripeId);
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
