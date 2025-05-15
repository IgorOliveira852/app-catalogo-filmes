<?php

namespace App\Services\TMDB;

interface TheMovieDBInterface
{
    public function search(string $endpoint, string $query, int $page = 1): array;
    public function getGenres(): array;
    public function getMovieDetails(int $movieId): array;
    public function searchWithFilters(string $endpoint, array $params): array;
    public function getMoviesList(string $endpoint): array;
    public function getMovie(int $movieId): array;
}
