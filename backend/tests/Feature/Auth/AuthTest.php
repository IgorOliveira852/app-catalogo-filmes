<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('user try to login with an account already exist', function () {
    $user = User::factory()->create(['password' => '123456']);
    Sanctum::actingAs(
        $user,
        ['*']
    );

    /* @var TestResponse $response */
    $response = $this->postJson(route('auth'), ['email' => $user->email, 'password' => '123456']);
    $response->assertStatus(200);
});

test('user try to login with an account doesnt exist', function () {
    /* @var TestResponse $response */
    $response = $this->postJson(route('auth'), ['email' => 'user@test.com', 'password' => '123456']);
    $response->assertStatus(422);
});
