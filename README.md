# StudList

StudList is a website that will allow users to put up ads for cattle, genetics (semen straws) and equipment.

Users can do this through...
1. creating the listing (creating a record(s) in the database)
2. paying for the listing (subscribe to a recurring fee of $15 through Stripe and Laravel Cashier to keep the listing live)

Note: Genetics, Show Equipment, and Services listings are free to post.

The user doesn't need to pay for the listing when they create it, but it won't show until they do.

The user can cancel the listing at any point, which will cancel the subscription. They can also

## Listing Details

- Steers
    - Name
    - Photos (multiple)
    - Date of Birth
    - Breed
    - Colour
    - Location (nearest town)
    - Sire
    - Dam
    - Contact (Business, Phone and/or Email) (autofill from the users profile) (need to have one or the other)
    - PIC number (autofill from the users profile, if they have previously added it)
    - Description
    - Started on feed? (checkbox)
    - Price
- Stud
    - Name
    - Photos (multiple)
    - Date of Birth
    - Breed
    - Colour
    - Tattoo number
    - Location (nearest town)
    - Sire
    - Dam
    - Registration link
    - Contact (Business, Phone and/or Email)
    - PIC number (autofill from the users profile, if they have previously added it)
    - Description
- Genetics
    - Name
    - Price
    - Photos (multiple)
    - Breed
    - Sire
    - Dam
    - Contact (Business, Phone and/or Email)
    - Storage location
    - Storage business (on-farm, NHD, etc.)
    - Description
- Show Equipment
    - Photos (multiple)
    - Condition
    - Location
    - Title
    - Description
    - Contact: phone and/or email
- Services
    - NOTE: Services can be listed for free
    - Type (photographer, fitter & feeder)
    - ABN
    - Business Name
    - Contact Name (autofill from the users profile)
    - Contact Phone and/or Email (autofill from the users profile)
    - Locations they cover (multiple, states, checkboxes)
    - Link(s)

## User Stories

### Authentication
- User can login
- User can logout
- User can register

### Listings
- User can create a listing
- User can edit a listing
- User can delete a listing
- User can view a listing
- User can view their listings (/dashboard)

### Subscriptions
- User can subscribe to a listing (show the listing publicly)
- User can pause a subscription
- User can cancel a subscription
- User can view their billing portal

## Tech Stack

- Laravel 12
- Inertia JS 2
- React 19
- Tailwind CSS 4
- ShadCn UI
- Stripe
- Laravel Cashier
- Lucide Icons

## Features

### Critical

- [x] Setup Steer listings
- [x] Setup Stud listings
- [x] Setup Genetics listings (free)
- [x] Setup Show Equipment listings (free)
- [x] Setup Services listings (free)
- [x] Setup Filament admin panel
- [x] Setup Stripe webhook (for both Steer and Stud listings)

### Nice to have

- [ ] Prefill the form with the user's profile data
- [ ] Track pageviews for each listing
- [ ] Show pageviews on the listing page on the listings show page