<?php

use App\Models\User;

describe('User Registration', function () {
    it('can view the registration page', function () {
        $page = $this->visit('/register');

        $page->assertSee('Get started')
            ->assertSee('Full name')
            ->assertSee('Email address')
            ->assertSee('Password');
    });

    it('can register a new user', function () {
        $page = $this->visit('/register');

        $page->fill('name', 'Test User')
            ->fill('email', 'newuser@example.com')
            ->fill('password', 'password123')
            ->fill('password_confirmation', 'password123')
            ->click('Create account');

        // Wait for navigation and assert
        $page->assertPathIs('/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'Test User',
        ]);
    });

    it('shows validation errors for invalid registration', function () {
        $page = $this->visit('/register');

        $page->fill('name', '')
            ->fill('email', 'invalid-email')
            ->fill('password', 'short')
            ->fill('password_confirmation', 'different')
            ->click('Create account');

        // Should show validation errors
        $page->assertPathIs('/register');
    });

    it('prevents duplicate email registration', function () {
        User::factory()->create(['email' => 'existing@example.com']);

        $page = $this->visit('/register');

        $page->fill('name', 'Duplicate User')
            ->fill('email', 'existing@example.com')
            ->fill('password', 'password123')
            ->fill('password_confirmation', 'password123')
            ->click('Create account');

        // Should show email taken error
        $page->assertPathIs('/register');
    });
});

describe('User Login', function () {
    it('can view the login page', function () {
        $page = $this->visit('/login');

        $page->assertSee('Welcome back')
            ->assertSee('Email address')
            ->assertSee('Password')
            ->assertSee('Forgot password?');
    });

    it('can login with valid credentials', function () {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $page = $this->visit('/login');

        $page->fill('email', 'test@example.com')
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertPathIs('/dashboard')
            ->assertSee('Dashboard');

        $this->assertAuthenticatedAs($user);
    });

    it('shows error for invalid credentials', function () {
        User::factory()->create([
            'email' => 'test2@example.com',
        ]);

        $page = $this->visit('/login');

        $page->fill('email', 'test2@example.com')
            ->fill('password', 'wrong-password')
            ->click('Log in');

        $page->assertPathIs('/login');
    });

    it('can remember user login', function () {
        User::factory()->create([
            'email' => 'remember@example.com',
        ]);

        $page = $this->visit('/login');

        $page->fill('email', 'remember@example.com')
            ->fill('password', 'password')
            ->click('Remember me')
            ->click('Log in');

        $page->assertPathIs('/dashboard');
    });
});

describe('User Logout', function () {
    it('can logout from dashboard', function () {
        $user = User::factory()->create();

        $page = $this->visit('/login');

        $page->fill('email', $user->email)
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertPathIs('/dashboard');

        // Submit the logout form
        $this->post(route('logout'))->assertRedirect('/');

        $this->assertGuest();
    });
});

describe('Password Reset', function () {
    it('can view forgot password page', function () {
        $page = $this->visit('/forgot-password');

        $page->assertSee('Reset your password')
            ->assertSee('Email address');
    });

    it('can request password reset link', function () {
        User::factory()->create([
            'email' => 'reset@example.com',
        ]);

        $page = $this->visit('/forgot-password');

        $page->fill('email', 'reset@example.com')
            ->click('Send reset link');

        // Should show success message or stay on page
        $page->assertPathIs('/forgot-password');
    });

    it('shows error for unknown email', function () {
        $page = $this->visit('/forgot-password');

        $page->fill('email', 'unknown@example.com')
            ->click('Send reset link');

        // Should show validation error or stay on page
        $page->assertPathIs('/forgot-password');
    });
});

describe('Protected Routes', function () {
    it('redirects unauthenticated users to login', function () {
        $page = $this->visit('/dashboard');

        $page->assertPathIs('/login');
    });

    it('redirects to intended page after login', function () {
        User::factory()->create([
            'email' => 'intended@example.com',
        ]);

        // Try to access dashboard without auth
        $page = $this->visit('/dashboard');
        $page->assertPathIs('/login');

        // Login
        $page->fill('email', 'intended@example.com')
            ->fill('password', 'password')
            ->click('Log in');

        $page->assertPathIs('/dashboard');
    });
});
