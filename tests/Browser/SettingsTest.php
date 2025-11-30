<?php

use App\Models\User;

describe('Profile Settings', function () {
    it('can view the profile settings page', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page = $this->visit('/settings/profile');

        $page->assertSee('Profile')
            ->assertSee('Name')
            ->assertSee('Email');
    });
});

describe('Password Settings', function () {
    it('can view the password settings page', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page = $this->visit('/settings/password');

        $page->assertSee('Password')
            ->assertSee('Current');
    });
});

describe('Settings Navigation', function () {
    it('can navigate between settings pages', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page = $this->visit('/settings/profile');

        $page->assertSee('Profile');

        $page = $this->visit('/settings/password');

        $page->assertSee('Password');
    });
});
