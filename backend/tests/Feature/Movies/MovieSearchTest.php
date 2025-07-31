<?php

namespace Tests\Feature\Movies;

use App\Models\User;
use App\Services\TMDB\TheMovieDBInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

test('search returns a collection of movies', function () {
    $fakeResults = [
        ['id'=>42, 'title'=>'The Answer', 'poster_path'=>null, 'overview'=>'…', 'release_date'=>'2020-04-02', 'vote_average'=>7.5],
        ['id'=>7,  'title'=>'Lucky 7',    'poster_path'=>null, 'overview'=>'…', 'release_date'=>'2019-11-11', 'vote_average'=>6.3],
    ];

    $this->mock(TheMovieDBInterface::class, function ($mock) use ($fakeResults) {
        $mock
            ->shouldReceive('searchWithFilters')
            ->once()
            ->andReturn(['results' => $fakeResults]);
    });

    $response = $this->getJson(route('movies.search', ['query' => 'anything']));

    $response
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment(['id' => 42, 'title' => 'The Answer']);
});

test('search aborts 404 when no results', function () {
    $this->mock(TheMovieDBInterface::class, function ($mock) {
        $mock->shouldReceive('searchWithFilters')->andReturn(['results' => []]);
    });

    $this->getJson(route('movies.search', ['query' => 'nothing']))
        ->assertStatus(404)
        ->assertSeeText('Nenhum resultado encontrado');
});
