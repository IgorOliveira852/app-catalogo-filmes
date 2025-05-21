<?php

class Helpers
{
    // Função para autenticar um usuário e retornar o token nos outros tests
    private static function authenticateUserAndGetToken($email, $password): string
    {
        $response = test()->postJson(route('auth'), [
            'email' => $email,
            'password' => $password
        ]);

        $response->assertStatus(200);
        return $response->json()['token']['access_token'];
    }

    public static function sendAuthenticatedRequest($user, $method, $route, $data = [])
    {
        $token = self::authenticateUserAndGetToken($user->email, 'password');

        return test()->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->$method($route, $data);
    }
}
