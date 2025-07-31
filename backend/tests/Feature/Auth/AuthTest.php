<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    User::factory()->create([
        'email'    => 'user@test.com',
        'password' => bcrypt('12345678'),
    ]);
});

test('authenticates with valid credentials', function () {
    $response = $this->postJson(route('auth'), [
        'email'    => 'user@test.com',
        'password' => '12345678',
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'token',
            'user' => ['id', 'name', 'email'],
        ]);
});

dataset('invalid logins', [
    ['wrong@test.com', '12345678'],
    ['user@test.com',   'badpassword'],
]);

test('fails with invalid credentials', function (string $email, string $password) {
    $response = $this->postJson(route('auth'), [
        'email'    => $email,
        'password' => $password,
    ]);

    if ($email === 'user@test.com') {
        $response
            ->assertStatus(401)
            ->assertJsonPath('message', 'Credenciais inválidas.');
    } else {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJsonPath('message', 'E-mail e/ou senha informados são inválido(s)!');
    }
})->with('invalid logins');
