<?php

namespace Tests\Integration\Movies;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

test('integration: search consulta a API TMDB e retorna os dados', function () {
    Http::fake([
        'api.themoviedb.org/*' => Http::response([
            'results' => [
                [
                    'id'           => 100,
                    'title'        => 'Mock Movie',
                    'poster_path'  => '/mock.jpg',
                    'overview'     => 'Enredo simulado',
                    'release_date' => '2021-01-01',
                    'vote_average' => 9.0,
                ]
            ]
        ], 200)
    ]);

    $response = $this->getJson(route('movies.search', ['query' => 'Mock']));

    $response
        ->assertStatus(200)
        ->assertJson([
            [
                'id'    => 100,
                'title' => 'Mock Movie',
            ]
        ]);
});
