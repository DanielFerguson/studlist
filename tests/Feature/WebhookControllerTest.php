<?php

use App\Http\Controllers\WebhookController;
use App\Models\SteerListing;
use App\Models\StudListing;
use App\Models\User;
use Illuminate\Support\Facades\Config;

describe('Webhook Controller Steer Listing Updates', function () {
    beforeEach(function () {
        // Set up test Stripe configuration
        Config::set('cashier.key', 'pk_test_fake');
        Config::set('cashier.secret', 'sk_test_fake');
    });

    test('updates steer listing to active when subscription is created', function () {
        $user = User::factory()->create(['stripe_id' => 'cus_test123']);
        $steer = SteerListing::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
            'stripe_subscription_id' => 'sub_test123',
        ]);

        // Use reflection to test private method
        $controller = new WebhookController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('updateSteerListingFromSubscription');
        $method->setAccessible(true);

        $subscription = [
            'id' => 'sub_test123',
            'status' => 'active',
        ];

        $method->invoke($controller, $subscription, 'active');
        
        $steer->refresh();
        $this->assertEquals('active', $steer->status);
    });

    test('updates steer listing status based on stripe status mapping', function () {
        $user = User::factory()->create(['stripe_id' => 'cus_test123']);
        
        $controller = new WebhookController();
        $reflection = new \ReflectionClass($controller);
        $mapMethod = $reflection->getMethod('mapStripeStatusToSteerStatus');
        $mapMethod->setAccessible(true);
        $updateMethod = $reflection->getMethod('updateSteerListingFromSubscription');
        $updateMethod->setAccessible(true);

        $statusTests = [
            'active' => 'active',
            'canceled' => 'cancelled',
            'incomplete_expired' => 'cancelled',
            'unpaid' => 'cancelled',
            'paused' => 'cancelled',
            'incomplete' => 'cancelled',
            'past_due' => 'active',  // Keep active but might need payment update
            'trialing' => 'active',  // Trial subscriptions are active
        ];

        foreach ($statusTests as $stripeStatus => $expectedStatus) {
            // Test the mapping
            $mappedStatus = $mapMethod->invoke($controller, $stripeStatus);
            $this->assertEquals($expectedStatus, $mappedStatus, "Failed mapping for: {$stripeStatus}");

            // Test the update
            $steer = SteerListing::factory()->create([
                'user_id' => $user->id,
                'status' => 'draft',
                'stripe_subscription_id' => "sub_{$stripeStatus}",
            ]);

            $subscription = [
                'id' => "sub_{$stripeStatus}",
                'status' => $stripeStatus,
            ];

            $updateMethod->invoke($controller, $subscription, $stripeStatus);
            
            $steer->refresh();
            $this->assertEquals($expectedStatus, $steer->status);
        }
    });

    test('handles missing steer listing gracefully', function () {
        $controller = new WebhookController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('updateSteerListingFromSubscription');
        $method->setAccessible(true);

        $subscription = [
            'id' => 'sub_nonexistent',
            'status' => 'active',
        ];

        // Should not throw exception
        $method->invoke($controller, $subscription, 'active');
        
        $this->assertTrue(true); // If we get here, no exception was thrown
    });

    test('updates correct steer listing when user has multiple', function () {
        $user = User::factory()->create(['stripe_id' => 'cus_test123']);
        
        $steer1 = SteerListing::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
            'stripe_subscription_id' => 'sub_test1',
        ]);
        
        $steer2 = SteerListing::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
            'stripe_subscription_id' => 'sub_test2',
        ]);

        $controller = new WebhookController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('updateSteerListingFromSubscription');
        $method->setAccessible(true);

        $subscription = [
            'id' => 'sub_test2',
            'status' => 'active',
        ];

        $method->invoke($controller, $subscription, 'active');
        
        $steer1->refresh();
        $steer2->refresh();
        
        // Only steer2 should be updated
        $this->assertEquals('draft', $steer1->status);
        $this->assertEquals('active', $steer2->status);
    });
});

describe('Webhook Controller Stud Listing Support', function () {
    test('webhook controller currently only handles steer listings', function () {
        $user = User::factory()->create(['stripe_id' => 'cus_test123']);
        $stud = StudListing::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
            'stripe_subscription_id' => 'sub_stud123',
        ]);

        $controller = new WebhookController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('updateSteerListingFromSubscription');
        $method->setAccessible(true);

        $subscription = [
            'id' => 'sub_stud123',
            'status' => 'active',
        ];

        $method->invoke($controller, $subscription, 'active');
        
        $stud->refresh();
        // Stud listing should not be updated since controller only handles steers
        $this->assertEquals('draft', $stud->status);
        
        // Verify no steer listing exists with this subscription
        $steer = SteerListing::where('stripe_subscription_id', 'sub_stud123')->first();
        $this->assertNull($steer);
    });
});