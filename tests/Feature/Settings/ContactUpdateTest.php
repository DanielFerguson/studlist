<?php

use App\Models\User;

test('contact settings page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/settings/contact');

    $response->assertOk();
});

test('contact information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/settings/contact', [
            'contact_business_name' => 'My Stud Farm',
            'contact_phone' => '0412345678',
            'contact_email' => 'contact@example.com',
            'contact_pic_number' => 'N123456',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/contact');

    $user->refresh();

    expect($user->contact_business_name)->toBe('My Stud Farm');
    expect($user->contact_phone)->toBe('0412345678');
    expect($user->contact_email)->toBe('contact@example.com');
    expect($user->contact_pic_number)->toBe('N123456');
});

test('contact information fields can be cleared', function () {
    $user = User::factory()->create([
        'contact_business_name' => 'Existing Business',
        'contact_phone' => '0400000000',
        'contact_email' => 'old@example.com',
        'contact_pic_number' => 'N111111',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/settings/contact', [
            'contact_business_name' => '',
            'contact_phone' => '0499999999',
            'contact_email' => '',
            'contact_pic_number' => '',
        ]);

    $response->assertSessionHasNoErrors();

    $user->refresh();

    expect($user->contact_phone)->toBe('0499999999');
    expect($user->contact_business_name)->toBeNull();
    expect($user->contact_email)->toBeNull();
    expect($user->contact_pic_number)->toBeNull();
});

test('contact email validates as email format', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/settings/contact')
        ->patch('/settings/contact', [
            'contact_email' => 'not-a-valid-email',
        ]);

    $response->assertSessionHasErrors('contact_email');
});

test('guests cannot access contact settings', function () {
    $response = $this->get('/settings/contact');

    $response->assertRedirect('/login');
});
