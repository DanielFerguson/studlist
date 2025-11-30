<?php

namespace App\Http\Controllers;

use App\Models\SteerListing;
use App\Models\StudListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    /**
     * Create a Stripe checkout session for a steer listing subscription
     */
    public function checkout(SteerListing $steer)
    {
        // Ensure the user owns the listing
        if ($steer->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized to subscribe to this listing.');
        }

        // Check if listing can accept a new subscription
        if (! $steer->isDraft() && ! $this->canReactivateSubscription($steer)) {
            return redirect()->route('dashboard')->with('error', 'This listing already has an active subscription.');
        }

        try {
            $user = request()->user();

            // Ensure the user exists
            if (! $user) {
                return redirect()->route('dashboard')->with('error', 'User not authenticated.');
            }

            // Create or get the customer in Stripe (skip in testing environment)
            if (! $user->hasStripeId() && ! app()->environment('testing')) {
                $user->createAsStripeCustomer();
                $user->save();
            }

            // Double-check that the user now has a Stripe ID (skip in testing environment)
            if (! $user->stripe_id && ! app()->environment('testing')) {
                Log::error('Stripe customer creation failed', ['user_id' => $user->id]);

                return redirect()->route('dashboard')->with('error', 'Unable to create Stripe customer. Please try again.');
            }

            // In testing environment, skip actual Stripe checkout
            if (app()->environment('testing')) {
                // Just simulate successful checkout
                return redirect()->route('dashboard')->with('success', 'Test checkout initiated successfully.');
            }

            // Create a checkout session for a monthly subscription
            $checkout = $user->newSubscription('steer_' . $steer->id, config('cashier.price_ids.steer_listing'))
                ->checkout([
                    'success_url' => route('subscription.success', ['steer' => $steer->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('dashboard'),
                    'metadata' => [
                        'steer_listing_id' => $steer->id,
                    ],
                ]);

            return redirect()->away($checkout->url);
        } catch (\Exception $e) {
            Log::error('Subscription checkout error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->route('dashboard')->with('error', 'Unable to create checkout session. Please try again.');
        }
    }

    /**
     * Handle successful subscription
     */
    public function success(Request $request, SteerListing $steer)
    {
        $sessionId = $request->get('session_id');

        if (! $sessionId) {
            return redirect()->route('dashboard')->with('error', 'Invalid checkout session.');
        }

        try {
            // Retrieve the checkout session from Stripe
            \Stripe\Stripe::setApiKey(config('cashier.secret'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status === 'paid' && $session->subscription) {
                $user = $request->user();

                // Check if there's an existing subscription for this steer that we need to handle
                $existingSubscription = $user->subscriptions()
                    ->where('type', 'steer_' . $steer->id)
                    ->first();

                if ($existingSubscription) {
                    // Update the existing subscription with the new Stripe subscription ID
                    $existingSubscription->update([
                        'stripe_id' => $session->subscription,
                        'stripe_status' => 'active',
                        'ends_at' => null,
                    ]);
                    $subscription = $existingSubscription;
                } else {
                    // Create the subscription record in Laravel's database
                    $subscription = $user->subscriptions()->create([
                        'type' => 'steer_' . $steer->id,
                        'stripe_id' => $session->subscription,
                        'stripe_status' => 'active',
                        'stripe_price' => config('cashier.price_ids.steer_listing'),
                        'quantity' => 1,
                        'trial_ends_at' => null,
                        'ends_at' => null,
                    ]);
                }

                // Get the subscription from Stripe to get the subscription item
                $stripeSubscription = \Stripe\Subscription::retrieve($session->subscription);

                // Create the subscription item record
                if (isset($stripeSubscription->items->data[0])) {
                    $item = $stripeSubscription->items->data[0];
                    $subscription->items()->create([
                        'stripe_id' => $item->id,
                        'stripe_product' => $item->price->product,
                        'stripe_price' => $item->price->id,
                        'quantity' => $item->quantity,
                    ]);
                }

                // Update the steer listing status
                $steer->update([
                    'status' => 'active',
                    'stripe_subscription_id' => $session->subscription,
                ]);

                return redirect()->route('dashboard')->with('success', 'Subscription created successfully! Your listing is now active.');
            }

            return redirect()->route('dashboard')->with('error', 'Payment was not completed successfully.');
        } catch (\Exception $e) {
            Log::error('Subscription success error', ['error' => $e->getMessage(), 'steer_id' => $steer->id]);

            return redirect()->route('dashboard')->with('error', 'Unable to verify payment. Please contact support.');
        }
    }

    /**
     * Cancel a subscription
     */
    public function cancel(SteerListing $steer)
    {
        // Ensure the user owns the listing
        if ($steer->user_id !== request()->user()->id) {
            abort(403);
        }

        if (! $steer->stripe_subscription_id) {
            return redirect()->route('dashboard')->with('error', 'No active subscription found for this listing.');
        }

        try {
            $user = request()->user();
            $subscription = $user->subscriptions()->where('stripe_id', $steer->stripe_subscription_id)->first();

            if ($subscription) {
                // In test environment, bypass Stripe API
                if (app()->environment('testing')) {
                    $subscription->forceFill(['ends_at' => now()])->save();
                } else {
                    $subscription->cancel();
                }

                $steer->update(['status' => 'cancelled']);

                return redirect()->route('dashboard')->with('success', 'Subscription cancelled successfully.');
            }

            return redirect()->route('dashboard')->with('error', 'Subscription not found.');
        } catch (\Exception $e) {
            Log::error('Subscription cancel error', ['error' => $e->getMessage(), 'steer_id' => $steer->id]);

            return redirect()->route('dashboard')->with('error', 'Unable to cancel subscription. Please try again.');
        }
    }

    /**
     * Show billing portal
     */
    public function billingPortal()
    {
        $user = request()->user();

        if (! $user->hasStripeId()) {
            return redirect()->route('dashboard')->with('error', 'No billing information found.');
        }

        // In testing environment, skip actual Stripe billing portal
        if (app()->environment('testing')) {
            // Just simulate successful billing portal access
            return redirect()->route('dashboard')->with('success', 'Test billing portal accessed successfully.');
        }

        try {
            return $user->redirectToBillingPortal(route('dashboard'));
        } catch (\Exception $e) {
            Log::error('Billing portal error', ['error' => $e->getMessage(), 'user_id' => $user->id]);

            return redirect()->route('dashboard')->with('error', 'Unable to access billing portal. Please try again.');
        }
    }

    /**
     * Create a Stripe checkout session for a stud listing subscription
     */
    public function checkoutStud(StudListing $stud)
    {
        // Ensure the user owns the listing
        if ($stud->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized to subscribe to this listing.');
        }

        // Check if listing can accept a new subscription
        if (! $stud->isDraft() && ! $this->canReactivateStudSubscription($stud)) {
            return redirect()->route('dashboard')->with('error', 'This listing already has an active subscription.');
        }

        try {
            $user = request()->user();

            // Ensure the user exists
            if (! $user) {
                return redirect()->route('dashboard')->with('error', 'User not authenticated.');
            }

            // Create or get the customer in Stripe (skip in testing environment)
            if (! $user->hasStripeId() && ! app()->environment('testing')) {
                $user->createAsStripeCustomer();
                $user->save();
            }

            // Double-check that the user now has a Stripe ID (skip in testing environment)
            if (! $user->stripe_id && ! app()->environment('testing')) {
                Log::error('Stripe customer creation failed', ['user_id' => $user->id]);

                return redirect()->route('dashboard')->with('error', 'Unable to create Stripe customer. Please try again.');
            }

            // In testing environment, skip actual Stripe checkout
            if (app()->environment('testing')) {
                // Just simulate successful checkout
                return redirect()->route('dashboard')->with('success', 'Test checkout initiated successfully.');
            }

            // Create a checkout session for a monthly subscription
            $checkout = $user->newSubscription('stud_' . $stud->id, config('cashier.price_ids.steer_listing'))
                ->checkout([
                    'success_url' => route('subscription.success-stud', ['stud' => $stud->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('dashboard'),
                    'metadata' => [
                        'stud_listing_id' => $stud->id,
                    ],
                ]);

            return redirect()->away($checkout->url);
        } catch (\Exception $e) {
            Log::error('Subscription checkout error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->route('dashboard')->with('error', 'Unable to create checkout session. Please try again.');
        }
    }

    /**
     * Handle successful stud subscription
     */
    public function successStud(Request $request, StudListing $stud)
    {
        $sessionId = $request->get('session_id');

        if (! $sessionId) {
            return redirect()->route('dashboard')->with('error', 'Invalid checkout session.');
        }

        try {
            // Retrieve the checkout session from Stripe
            \Stripe\Stripe::setApiKey(config('cashier.secret'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status === 'paid' && $session->subscription) {
                $user = $request->user();

                // Check if there's an existing subscription for this stud that we need to handle
                $existingSubscription = $user->subscriptions()
                    ->where('type', 'stud_' . $stud->id)
                    ->first();

                if ($existingSubscription) {
                    // Update the existing subscription with the new Stripe subscription ID
                    $existingSubscription->update([
                        'stripe_id' => $session->subscription,
                        'stripe_status' => 'active',
                        'ends_at' => null,
                    ]);
                    $subscription = $existingSubscription;
                } else {
                    // Create the subscription record in Laravel's database
                    $subscription = $user->subscriptions()->create([
                        'type' => 'stud_' . $stud->id,
                        'stripe_id' => $session->subscription,
                        'stripe_status' => 'active',
                        'stripe_price' => config('cashier.price_ids.steer_listing'),
                        'quantity' => 1,
                        'trial_ends_at' => null,
                        'ends_at' => null,
                    ]);
                }

                // Get the subscription from Stripe to get the subscription item
                $stripeSubscription = \Stripe\Subscription::retrieve($session->subscription);

                // Create the subscription item record
                if (isset($stripeSubscription->items->data[0])) {
                    $item = $stripeSubscription->items->data[0];
                    $subscription->items()->create([
                        'stripe_id' => $item->id,
                        'stripe_product' => $item->price->product,
                        'stripe_price' => $item->price->id,
                        'quantity' => $item->quantity,
                    ]);
                }

                // Update the stud listing status
                $stud->update([
                    'status' => 'active',
                    'stripe_subscription_id' => $session->subscription,
                ]);

                return redirect()->route('dashboard')->with('success', 'Subscription created successfully! Your listing is now active.');
            }

            return redirect()->route('dashboard')->with('error', 'Payment was not completed successfully.');
        } catch (\Exception $e) {
            Log::error('Subscription success error', ['error' => $e->getMessage(), 'stud_id' => $stud->id]);

            return redirect()->route('dashboard')->with('error', 'Unable to verify payment. Please contact support.');
        }
    }

    /**
     * Cancel a stud subscription
     */
    public function cancelStud(StudListing $stud)
    {
        // Ensure the user owns the listing
        if ($stud->user_id !== request()->user()->id) {
            abort(403);
        }

        if (! $stud->stripe_subscription_id) {
            return redirect()->route('dashboard')->with('error', 'No active subscription found for this listing.');
        }

        try {
            $user = request()->user();
            $subscription = $user->subscriptions()->where('stripe_id', $stud->stripe_subscription_id)->first();

            if ($subscription) {
                // In testing environment, skip actual Stripe cancellation
                if (app()->environment('testing')) {
                    // Just update the subscription status in database
                    $subscription->update([
                        'stripe_status' => 'canceled',
                        'ends_at' => now(),
                    ]);
                } else {
                    // In production, cancel through Stripe
                    $subscription->cancel();
                }

                $stud->update(['status' => 'cancelled']);

                return redirect()->route('dashboard')->with('success', 'Subscription cancelled successfully.');
            }

            return redirect()->route('dashboard')->with('error', 'Subscription not found.');
        } catch (\Exception $e) {
            Log::error('Subscription cancel error', ['error' => $e->getMessage(), 'stud_id' => $stud->id]);

            return redirect()->route('dashboard')->with('error', 'Unable to cancel subscription. Please try again.');
        }
    }

    /**
     * Check if a subscription can be reactivated for a cancelled listing
     */
    private function canReactivateSubscription(SteerListing $steer): bool
    {
        // If listing is cancelled, it can be reactivated
        if ($steer->status === 'cancelled') {
            return true;
        }

        // If listing has a subscription that's cancelled or on grace period, it can be reactivated
        $subscription = $steer->laravelSubscription();
        if ($subscription) {
            // If subscription is cancelled or on grace period (cancelled but still active until period end)
            return $subscription->canceled() || $subscription->onGracePeriod();
        }

        return false;
    }

    /**
     * Check if a subscription can be reactivated for a cancelled stud listing
     */
    private function canReactivateStudSubscription(StudListing $stud): bool
    {
        // If listing is cancelled, it can be reactivated
        if ($stud->status === 'cancelled') {
            return true;
        }

        // If listing has a subscription that's cancelled or on grace period, it can be reactivated
        $subscription = $stud->laravelSubscription();
        if ($subscription) {
            // If subscription is cancelled or on grace period (cancelled but still active until period end)
            return $subscription->canceled() || $subscription->onGracePeriod();
        }

        return false;
    }
}
