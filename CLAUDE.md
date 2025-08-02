# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

StudList is a marketplace application for cattle listings, allowing users to advertise steers, studs, genetics, equipment, and services. Users create listings and pay a monthly subscription ($15/month) via Stripe to keep their listings visible to buyers.

More information about the project can be found in the @README.md file.

## Tech Stack

- **Backend**: Laravel 12 with PHP 8.2+
- **Frontend**: React 19 with TypeScript
- **SPA Framework**: Inertia.js 2
- **UI Components**: ShadCN UI with Radix UI primitives (members area), TailGrids Tailwind UI (publicly available pages)
- **Styling**: Tailwind CSS 4
- **Icons**: Lucide React
- **Payments**: Stripe with Laravel Cashier
- **Database**: SQLite (development), configurable for production
- **Testing**: Pest PHP

## Development Commands

### Start Development Environment

```bash
# Starts Laravel server, queue worker, logs, and Vite dev server concurrently
composer run dev

# For SSR development
composer run dev:ssr
```

### Build & Compile

```bash
# Build frontend assets
npm run build

# Build with SSR
npm run build:ssr
```

### Code Quality

```bash
# Run PHP tests
composer test
# or
php artisan test

# Run specific test
php artisan test --filter TestName

# Format PHP code
./vendor/bin/pint

# Format JavaScript/TypeScript code
npm run format

# Check formatting without fixing
npm run format:check

# Lint JavaScript/TypeScript
npm run lint

# Type checking
npm run types
```

### Database

```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### Stripe Testing

```bash
# Test subscription for a user and listing
php artisan test:subscription {user_id} {steer_id}
```

## Architecture Overview

### Backend Structure

- **Controllers**: Located in `app/Http/Controllers/`
  - Auth controllers for authentication
  - `SteerListingController` and `StudListingController` for listings
  - `SubscriptionController` for Stripe integration
  - `WebhookController` for Stripe webhooks
- **Models**: `User`, `SteerListing`, `StudListing` with Eloquent ORM
- **Policies**: Authorization logic for listings
- **Requests**: Form validation classes

### Frontend Structure

- **Pages**: React components in `resources/js/pages/`
  - Auth pages (login, register, password reset)
  - Dashboard for listing management
  - Settings pages (profile, password, appearance)
  - Listing creation/edit pages
- **Components**: Reusable UI in `resources/js/components/`
  - Form components for listings
  - UI primitives from ShadCN
  - App shell with sidebar navigation
- **Layouts**: Page layouts in `resources/js/layouts/`
  - AppLayout for authenticated pages
  - AuthLayout for authentication pages
  - Settings layout with sidebar

### Database Schema

- **Users**: Standard Laravel auth
- **Listings**: 
  - `steer_listings`: Cattle for sale
  - `stud_listings`: Breeding bulls
  - Status: `draft` (unpaid) or `active` (subscribed)
- **Subscriptions**: Laravel Cashier tables
  - `subscriptions`: Active subscriptions
  - `subscription_items`: Subscription line items

## Key Development Patterns

### Inertia.js Pages

```php
// Backend (Controller)
return Inertia::render('PageName', [
    'data' => $data
]);

// Frontend (React Page)
export default function PageName({ data }) {
    // Component logic
}
```

### Form Handling

- Uses `react-hook-form` with Zod validation
- Form requests on backend for validation
- Inertia form handling for seamless submission

### Authentication

- Laravel's built-in auth with Inertia adaptations
- Protected routes use `auth` and `verified` middleware
- User menu and navigation components handle auth state

### File Uploads

- FilePond for image uploads
- Images stored in `storage/app/public/`
- Symlinked to `public/storage/`

## Subscription Flow

1. User creates listing (status: `draft`)
2. User clicks subscribe → Redirected to Stripe Checkout
3. Payment success → Webhook updates listing to `active`
4. Monthly billing handled by Stripe
5. User can cancel/pause via billing portal

## Important Configuration

### Environment Variables

Key variables to configure:
- `STRIPE_KEY`: Publishable key
- `STRIPE_SECRET`: Secret key
- `STRIPE_STEER_LISTING_PRICE_ID`: Price ID from Stripe
- `STRIPE_STUD_LISTING_PRICE_ID`: Price ID for stud listings
- `STRIPE_WEBHOOK_SECRET`: For production webhooks

### Code Style

- **PHP**: PSR-12 standard, enforced by Pint
- **TypeScript**: Prettier with Tailwind plugin
- **Indentation**: 4 spaces (2 for YAML)
- **React**: Functional components with hooks

## Testing Approach

- **Feature Tests**: Integration tests for user flows
- **Unit Tests**: Isolated component testing
- **Database**: Uses RefreshDatabase trait
- **Test Data**: Factories for models
- Run `php artisan test` for full suite

## Current Implementation Status

✅ Implemented:
- User authentication
- Steer listings (CRUD + subscriptions)
- Stud listings (CRUD + subscriptions)
- Stripe payment integration
- Basic dashboard
- Genetics listings
- Show equipment listings
- Services listings (free)
- Admin panel with Filament

🚧 Planned:
- Pageview tracking

## Coding Standards

When working on this Laravel/PHP project, first read the coding guidelines at @laravel-php-guidelines.md