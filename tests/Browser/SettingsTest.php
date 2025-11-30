<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

describe('Profile Settings', function () {
    it('can view profile settings page', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/settings/profile');

        $page->assertSee('Profile')
            ->assertSee('Name')
            ->assertSee('Email');
    });

    it('displays current user information', function () {
        $user = User::factory()->create([
            'name' => 'Settings Test User',
            'email' => 'settings-tester@example.com',
        ]);

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/settings/profile');

        $page->assertSee('Settings Test User')
            ->assertSee('settings-tester@example.com');
    });

    it('can update profile name', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/settings/profile');

        $page->fill('name', 'Updated Name')
            ->click('Save')
            ;

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    });

    it('can update profile email', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/settings/profile');

        $page->fill('email', 'newemail@example.com')
            ->click('Save')
            ;

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'newemail@example.com',
        ]);
    });

    it('shows validation error for invalid email', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/settings/profile');

        $page->fill('email', 'invalid-email')
            ->click('Save')
            ->assertSee('email');
    });
});

describe('Password Settings', function () {
    it('can view password settings page', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/settings/password');

        $page->assertSee('Password')
            ->assertSee('Current Password')
            ->assertSee('New Password')
            ->assertSee('Confirm Password');
    });

    it('can update password', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/settings/password');

        $page->fill('current_password', 'password')
            ->fill('password', 'newpassword123')
            ->fill('password_confirmation', 'newpassword123')
            ->click('Update Password')
            ;

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    });

    it('shows error for incorrect current password', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/settings/password');

        $page->fill('current_password', 'wrongpassword')
            ->fill('password', 'newpassword123')
            ->fill('password_confirmation', 'newpassword123')
            ->click('Update Password')
            ->assertSee('current');
    });

    it('shows error for mismatched password confirmation', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/settings/password');

        $page->fill('current_password', 'password')
            ->fill('password', 'newpassword123')
            ->fill('password_confirmation', 'differentpassword')
            ->click('Update Password')
            ->assertSee('password');
    });

    it('shows error for password too short', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/settings/password');

        $page->fill('current_password', 'password')
            ->fill('password', 'short')
            ->fill('password_confirmation', 'short')
            ->click('Update Password')
            ->assertSee('password');
    });
});

describe('Appearance Settings', function () {
    it('can view appearance settings page', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        $page = $this->visit('/settings/appearance');

        $page->assertSee('Appearance')
            ->assertSee('Theme');
    });
});

describe('Settings Navigation', function () {
    it('can navigate between settings pages', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        // Navigate to profile
        $page = $this->visit('/settings/profile');
        $page->assertSee('Profile');

        // Navigate to password
        $page->click('Password')
            ->waitForNavigation()
            ->assertPathIs('/settings/password');

        // Navigate to appearance
        $page->click('Appearance')
            ->waitForNavigation()
            ->assertPathIs('/settings/appearance');
    });

    it('can navigate to settings from dashboard', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');
        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in')
            ;

        // Click settings link in user menu or sidebar
        $page->click('Settings')
            ->waitForNavigation()
            ->assertPathIs('/settings/profile');
    });
});

describe('Settings Access Control', function () {
    it('redirects unauthenticated users to login', function () {
        $page = $this->visit('/settings/profile');

        $page->assertPathIs('/login');
    });

    it('redirects unauthenticated users from password settings', function () {
        $page = $this->visit('/settings/password');

        $page->assertPathIs('/login');
    });

    it('redirects unauthenticated users from appearance settings', function () {
        $page = $this->visit('/settings/appearance');

        $page->assertPathIs('/login');
    });
});
