<?php

use App\Models\User;

describe('Contact Defaults Method', function () {
    test('getContactDefaults returns saved contact info', function () {
        $user = User::factory()->create([
            'contact_business_name' => 'My Stud',
            'contact_phone' => '0412345678',
            'contact_email' => 'contact@mystud.com',
            'contact_pic_number' => 'N123456',
        ]);

        $defaults = $user->getContactDefaults();

        expect($defaults['business_contact'])->toBe('My Stud');
        expect($defaults['phone_contact'])->toBe('0412345678');
        expect($defaults['email_contact'])->toBe('contact@mystud.com');
        expect($defaults['pic_number'])->toBe('N123456');
    });

    test('getContactDefaults falls back to user email when contact_email is null', function () {
        $user = User::factory()->create([
            'email' => 'registered@example.com',
            'contact_email' => null,
        ]);

        $defaults = $user->getContactDefaults();

        expect($defaults['email_contact'])->toBe('registered@example.com');
    });

    test('getContactDefaults uses contact_email when set', function () {
        $user = User::factory()->create([
            'email' => 'registered@example.com',
            'contact_email' => 'contact@example.com',
        ]);

        $defaults = $user->getContactDefaults();

        expect($defaults['email_contact'])->toBe('contact@example.com');
    });
});

describe('Contact Auto-save on Listing Creation', function () {
    test('creating a steer listing saves contact info to user', function () {
        $user = User::factory()->create([
            'contact_business_name' => null,
            'contact_phone' => null,
            'contact_email' => null,
            'contact_pic_number' => null,
        ]);

        $this->actingAs($user)->post(route('steers.store'), [
            'name' => 'Test Steer',
            'dob' => '2023-01-15',
            'breed' => 'Angus',
            'colour' => 'Black',
            'location' => 'Armidale, NSW',
            'business_contact' => 'My Farm',
            'phone_contact' => '0412345678',
            'email_contact' => 'farm@example.com',
            'pic_number' => 'N999999',
        ]);

        $user->refresh();

        expect($user->contact_business_name)->toBe('My Farm');
        expect($user->contact_phone)->toBe('0412345678');
        expect($user->contact_email)->toBe('farm@example.com');
        expect($user->contact_pic_number)->toBe('N999999');
    });

    test('creating a genetics listing saves contact info to user', function () {
        $user = User::factory()->create([
            'contact_phone' => null,
            'contact_email' => null,
        ]);

        $this->actingAs($user)->post(route('genetics.store'), [
            'name' => 'Test Genetics',
            'price' => 150,
            'breed' => 'Angus',
            'type' => 'Semen Straws',
            'storage_location' => 'NSW',
            'phone_contact' => '0487654321',
            'email_contact' => 'genetics@example.com',
        ]);

        $user->refresh();

        expect($user->contact_phone)->toBe('0487654321');
        expect($user->contact_email)->toBe('genetics@example.com');
    });
});

describe('Contact Auto-fill on Create Form', function () {
    test('steer create form shows saved contact defaults', function () {
        $user = User::factory()->create([
            'contact_business_name' => 'Saved Business',
            'contact_phone' => '0411111111',
            'contact_email' => 'saved@example.com',
            'contact_pic_number' => 'N111111',
        ]);

        $response = $this->actingAs($user)->get(route('steers.create'));

        $response->assertOk();
        $response->assertSee('value="Saved Business"', false);
        $response->assertSee('value="0411111111"', false);
        $response->assertSee('value="saved@example.com"', false);
        $response->assertSee('value="N111111"', false);
    });

    test('first time user sees registered email as default', function () {
        $user = User::factory()->create([
            'email' => 'user@registered.com',
            'contact_email' => null,
        ]);

        $response = $this->actingAs($user)->get(route('steers.create'));

        $response->assertOk();
        $response->assertSee('value="user@registered.com"', false);
    });

    test('genetics create form shows saved contact defaults', function () {
        $user = User::factory()->create([
            'contact_phone' => '0422222222',
            'contact_email' => 'genetics@saved.com',
        ]);

        $response = $this->actingAs($user)->get(route('genetics.create'));

        $response->assertOk();
        $response->assertSee('value="0422222222"', false);
        $response->assertSee('value="genetics@saved.com"', false);
    });

    test('equipment create form shows saved contact defaults', function () {
        $user = User::factory()->create([
            'contact_phone' => '0433333333',
            'contact_email' => 'equipment@saved.com',
        ]);

        $response = $this->actingAs($user)->get(route('show-equipment.create'));

        $response->assertOk();
        $response->assertSee('value="0433333333"', false);
        $response->assertSee('value="equipment@saved.com"', false);
    });

    test('service create form shows saved contact defaults', function () {
        $user = User::factory()->create([
            'contact_phone' => '0444444444',
            'contact_email' => 'service@saved.com',
        ]);

        $response = $this->actingAs($user)->get(route('services.create'));

        $response->assertOk();
        $response->assertSee('value="0444444444"', false);
        $response->assertSee('value="service@saved.com"', false);
    });

    test('stud create form shows saved contact defaults', function () {
        $user = User::factory()->create([
            'contact_business_name' => 'Stud Business',
            'contact_phone' => '0455555555',
            'contact_email' => 'stud@saved.com',
            'contact_pic_number' => 'N555555',
        ]);

        $response = $this->actingAs($user)->get(route('studs.create'));

        $response->assertOk();
        $response->assertSee('value="Stud Business"', false);
        $response->assertSee('value="0455555555"', false);
        $response->assertSee('value="stud@saved.com"', false);
        $response->assertSee('value="N555555"', false);
    });
});



