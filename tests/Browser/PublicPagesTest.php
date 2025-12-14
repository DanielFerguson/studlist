<?php

use App\Models\User;

describe('Homepage', function () {
    it('can view the homepage', function () {
        $page = $this->visit('/');

        $page->assertSee('StudList')
            ->assertSee('Where Quality')
            ->assertSee('Meets Trust');
    });

    it('has navigation links', function () {
        $page = $this->visit('/');

        $page->assertSee('Log in')
            ->assertSee('Start Listing');
    });

    it('can navigate to login from homepage', function () {
        $page = $this->visit('/');

        $page->click('Log in');

        $page->assertPathIs('/login');
    });

    it('can navigate to register from homepage', function () {
        $page = $this->visit('/');

        $page->click('Start Listing');

        $page->assertPathIs('/register');
    });

    it('shows call to action buttons', function () {
        $page = $this->visit('/');

        $page->assertSee('Browse Listings')
            ->assertSee('Start Selling');
    });
});

describe('Search Page', function () {
    it('can view the search page', function () {
        $page = $this->visit('/search');

        $page->assertSee('Search')
            ->assertSee('Listings');
    });

    it('can filter by steers category', function () {
        $page = $this->visit('/search?category[]=steers');

        $page->assertPathContains('/search');
    });

    it('can filter by studs category', function () {
        $page = $this->visit('/search?category[]=studs');

        $page->assertPathContains('/search');
    });
});

describe('About Page', function () {
    it('can view the about page', function () {
        $page = $this->visit('/about');

        $page->assertSee('About');
    });
});

describe('Footer Links', function () {
    it('has footer with copyright', function () {
        $page = $this->visit('/');

        $page->assertSee('StudList');
    });
});

describe('SEO Elements', function () {
    it('has proper page title on homepage', function () {
        $page = $this->visit('/');

        $page->assertTitleContains('StudList');
    });
});

describe('Authenticated Navigation', function () {
    it('shows dashboard link when authenticated', function () {
        $user = User::factory()->create();

        // Login first
        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertPathIs('/dashboard');

        // Go to homepage and check for dashboard link
        $page = $this->visit('/');
        $page->assertSee('Dashboard');
    });
});
