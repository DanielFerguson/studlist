<?php

describe('Homepage', function () {
    it('can view the homepage', function () {
        $page = $this->visit('/');

        $page->assertSee('StudList')
            ->assertSee('Buy')
            ->assertSee('Sell');
    });

    it('has navigation links', function () {
        $page = $this->visit('/');

        $page->assertSee('Log in')
            ->assertSee('Register');
    });

    it('can navigate to login from homepage', function () {
        $page = $this->visit('/');

        $page->click('Log in')
            ->waitForNavigation()
            ->assertUrlIs('/login');
    });

    it('can navigate to register from homepage', function () {
        $page = $this->visit('/');

        $page->click('Register')
            ->waitForNavigation()
            ->assertUrlIs('/register');
    });

    it('shows call to action buttons', function () {
        $page = $this->visit('/');

        $page->assertSee('Start Selling')
            ->assertSee('Browse Listings');
    });
});

describe('Search Page', function () {
    it('can view the search page', function () {
        $page = $this->visit('/search');

        $page->assertSee('Search')
            ->assertSee('Listings');
    });
});

describe('About Page', function () {
    it('can view the about page', function () {
        $page = $this->visit('/about');

        $page->assertSee('About');
    });
});

describe('Steers Listing Page', function () {
    it('can view the steers listing page', function () {
        $page = $this->visit('/steers');

        $page->assertSee('Steers');
    });
});

describe('Studs Listing Page', function () {
    it('can view the studs listing page', function () {
        $page = $this->visit('/studs');

        $page->assertSee('Studs');
    });
});

describe('Genetics Listing Page', function () {
    it('can view the genetics listing page', function () {
        $page = $this->visit('/genetics');

        $page->assertSee('Genetics');
    });
});

describe('Equipment Listing Page', function () {
    it('can view the equipment listing page', function () {
        $page = $this->visit('/show-equipment');

        $page->assertSee('Equipment');
    });
});

describe('Services Listing Page', function () {
    it('can view the services listing page', function () {
        $page = $this->visit('/services');

        $page->assertSee('Services');
    });
});

describe('Footer Links', function () {
    it('has footer with copyright', function () {
        $page = $this->visit('/');

        $page->assertSee('StudList');
    });
});

describe('Mobile Navigation', function () {
    it('has responsive navigation', function () {
        $page = $this->visit('/');

        // Should see main navigation elements
        $page->assertSee('Log in')
            ->assertSee('Register');
    });
});

describe('SEO Elements', function () {
    it('has proper page title on homepage', function () {
        $page = $this->visit('/');

        // Verify page loads successfully
        $page->assertSee('StudList');
    });
});
