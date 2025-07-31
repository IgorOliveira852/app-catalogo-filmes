<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('registers a new user', function () {
    $data = [
        'name'     => 'Novo Usuário',
        'email'    => 'novo@teste.com',
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ];

    $response = $this->postJson(route('register'), $data);

    $response
        ->assertStatus(201)
        ->assertJson([
            'message' => 'User registered successfully',
            'user'    => ['email' => 'novo@teste.com']
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'novo@teste.com',
    ]);
});

test('fails with invalid email', function () {
    $response = $this->postJson(route('register'), [
        'name'     => 'Usuário',
        'email'    => 'invalid-email',
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('fails when email already taken', function () {
    User::factory()->create(['email' => 'dup@teste.com']);

    $response = $this->postJson(route('register'), [
        'name'     => 'Usuário',
        'email'    => 'dup@teste.com',
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
