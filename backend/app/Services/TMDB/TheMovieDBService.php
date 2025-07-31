<?php

namespace App\Services\TMDB;

use App\Services\TMDB\TheMovieDBInterface;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TheMovieDBService implements TheMovieDBInterface
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.base_url');
    }

    public function getMovie(int $movieId): array
    {
        return $this->makeRequest("movie/{$movieId}");
    }

    public function getMovieDetails(int $movieId): array
    {
        return $this->makeRequest("movie/{$movieId}", [
            'api_key' => $this->apiKey,
            'language' => 'pt-BR',
            'append_to_response' => 'credits,videos,images'
        ]);
    }

    public function search(string $endpoint, string $query, int $page = 1): array
    {
        return $this->makeRequest($endpoint, [
            'query' => $query,
            'page' => $page,
            'language' => 'pt-BR',
            'include_adult' => false,
        ]);
    }

    public function searchWithFilters(string $endpoint, array $params): array
    {
        return $this->makeRequest($endpoint, $params);
    }

    public function getGenres(): array
    {
        return Cache::remember('tmdb.genres', 60 * 24, fn() =>
            $this->makeRequest('genre/movie/list')['genres']
        );
    }

    public function getMoviesList(string $endpoint, int $page = 1): array
    {
        $cacheKey = "tmdb.{$endpoint}.page_{$page}";

        return Cache::remember($cacheKey, now()->addMinutes(30), fn() =>
            $this->makeRequest($endpoint, [
                'api_key'  => $this->apiKey,
                'language' => 'pt-BR',
                'page'     => $page,
            ])
        );
    }

    /**
     * Faz uma requisição para a API do The Movie Database (TMDB).
     *
     * @param string $endpoint O endpoint da API.
     * @param array $params Parâmetros adicionais para a requisição.
     * @return array A resposta da API em formato de array.
     * @throws Exception Se a requisição falhar.
     */
    private function makeRequest(string $endpoint, array $params = []): array
    {
        $params['api_key'] = $this->apiKey;
        $response = Http::get("{$this->baseUrl}/{$endpoint}", $params);

        if ($response->failed()) {
            throw new Exception("Erro na requisição: " . $response->body());
        }

        return $response->json();
    }

}
