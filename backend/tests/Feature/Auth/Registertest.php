<?php

use App\Models\User;

test('creates a new user successfully', function () {
    $user = User::factory()->create();

    $data = [
        'name' => 'Novo Usuário',
        'email' => 'novoemail@teste.com',
        'password' => '123456',
    ];

    $response = Helpers::sendAuthenticatedRequest($user, 'postJson', route('register'), $data);

    $response->assertStatus(201);
    $response->assertJson(['message' => 'Usuário criado com sucesso']);
    $this->assertDatabaseHas('users', [
        'email' => 'novoemail@teste.com',
    ]);
});

test('requires a valid email', function () {
    $user = User::factory()->create();

    $data = [
        'name' => 'Novo Usuário',
        'email' => 'invalid-email',
        'password' => '123456',
    ];

    $response = Helpers::sendAuthenticatedRequest($user, 'postJson', route('register'), $data);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});
