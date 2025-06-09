# Stripe Setup Guide for StudList

This guide will help you set up Stripe payments for the StudList application.

## Prerequisites

1. A Stripe account (sign up at https://stripe.com)
2. Laravel Cashier installed (already done)

## Setup Steps

### 1. Create a Product in Stripe

1. Log into your Stripe Dashboard
2. Go to **Products** in the left sidebar
3. Click **+ Add product**
4. Fill in the details:
   - **Name**: "Steer Listing Subscription"
   - **Description**: "Monthly subscription to keep steer listings active and visible to buyers"
   - **Pricing model**: Recurring
   - **Price**: $15.00 USD
   - **Billing period**: Monthly
5. Click **Save product**
6. Copy the **Price ID** (starts with `price_`)

### 2. Configure Environment Variables

Add these variables to your `.env` file:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here
STRIPE_STEER_LISTING_PRICE_ID=price_your_price_id_here

# Optional: Webhook configuration (for production)
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here

# Currency settings
CASHIER_CURRENCY=usd
CASHIER_CURRENCY_LOCALE=en
```

### 3. Test Keys vs Live Keys

- **Test Mode**: Use keys that start with `pk_test_` and `sk_test_`
- **Live Mode**: Use keys that start with `pk_live_` and `sk_live_`

### 4. Webhook Setup (Production Only)

For production, set up webhooks to handle subscription events:

1. In Stripe Dashboard, go to **Developers** > **Webhooks**
2. Click **+ Add endpoint**
3. Set endpoint URL to: `https://yourdomain.com/stripe/webhook`
4. Select these events:
   - `customer.subscription.created`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`
5. Copy the webhook signing secret and add it to your `.env` file

## How It Works

### Subscription Flow

1. **Create Listing**: User creates a steer listing (status: `draft`)
2. **Subscribe**: User clicks "Subscribe ($15/mo)" button
3. **Stripe Checkout**: User is redirected to Stripe-hosted checkout page
4. **Payment Success**: User is redirected back, subscription is saved to database, listing status becomes `active`
5. **Monthly Billing**: Stripe automatically charges $15/month

### Database Tracking

The application tracks subscriptions in two ways:

1. **Laravel's Database**: 
   - `subscriptions` table stores subscription records
   - `subscription_items` table stores subscription item details
   - Linked to users and provides local status tracking

2. **Steer Listings**:
   - `stripe_subscription_id` field links to Stripe subscription
   - `status` field reflects current subscription state
   - Automatically updated via webhooks

### Subscription Management

Users can:
- **Pause**: Temporarily pause subscription (listing becomes hidden)
- **Resume**: Resume a paused subscription
- **Cancel**: Permanently cancel subscription
- **Billing Portal**: Access Stripe's customer portal for payment methods, invoices, etc.

### Status Meanings

- `draft`: Listing created but not paid for (not visible publicly)
- `active`: Subscription active, listing visible to buyers
- `paused`: Subscription paused, listing hidden
- `cancelled`: Subscription cancelled, listing hidden

## Testing

Use Stripe's test card numbers:
- **Success**: `4242424242424242`
- **Decline**: `4000000000000002`
- **3D Secure**: `4000002500003155`

### Testing Database Integration

You can test the subscription database integration with:

```bash
# Check subscription status for a user and listing
php artisan test:subscription {user_id} {steer_id}

# Example
php artisan test:subscription 1 20
```

## Database Schema

### Subscriptions Table
- `id`: Primary key
- `user_id`: Foreign key to users
- `type`: Subscription type (e.g., "steer_20")
- `stripe_id`: Stripe subscription ID
- `stripe_status`: Current Stripe status
- `stripe_price`: Price ID from Stripe
- `quantity`: Number of items (usually 1)
- `trial_ends_at`: Trial end date (nullable)
- `ends_at`: Subscription end date (nullable)

### Subscription Items Table
- `id`: Primary key
- `subscription_id`: Foreign key to subscriptions
- `stripe_id`: Stripe subscription item ID
- `stripe_product`: Stripe product ID
- `stripe_price`: Stripe price ID
- `quantity`: Item quantity

## Security Notes

- Never commit real Stripe keys to version control
- Use test keys during development
- Set up proper webhook verification in production
- Consider implementing additional authorization checks

## Troubleshooting

### Common Issues

1. **"No such price"**: Check that your `STRIPE_STEER_LISTING_PRICE_ID` matches the Price ID in Stripe
2. **"No such customer"**: Ensure the user has a Stripe customer ID (created automatically on first subscription)
3. **Webhook failures**: Verify webhook URL is accessible and webhook secret is correct
4. **Subscription not in database**: Check that webhooks are properly configured and firing
5. **Status mismatch**: Ensure webhooks are updating the `steer_listings.status` field correctly

### Database Verification

Check if subscriptions are being saved correctly:

```sql
-- Check subscriptions in database
SELECT s.*, u.email FROM subscriptions s JOIN users u ON s.user_id = u.id;

-- Check steer listings with subscriptions
SELECT sl.name, sl.status, sl.stripe_subscription_id, s.stripe_status 
FROM steer_listings sl 
LEFT JOIN subscriptions s ON sl.stripe_subscription_id = s.stripe_id 
WHERE sl.stripe_subscription_id IS NOT NULL;
```

### Logs

Check Laravel logs for Stripe-related errors:
```bash
tail -f storage/logs/laravel.log
```

## Support

- Stripe Documentation: https://stripe.com/docs
- Laravel Cashier Documentation: https://laravel.com/docs/billing 