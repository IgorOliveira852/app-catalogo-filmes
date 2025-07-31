<?php

namespace App\Http\Controllers\Filter;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Services\TMDB\TheMovieDBInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetFiltersController extends Controller
{
    public function __construct(private readonly TheMovieDBInterface $tmdbService)
    {
    }

    public function __invoke(Request $request, string $slug): JsonResponse
    {
        return match ($slug) {
            'favorites' => $this->getFavorites(),
            'genres' => $this->getGenres(),
            'popular' => $this->getMovies('movie/popular'),
            'now_playing' => $this->getMovies('movie/now_playing'),
            'top_rated' => $this->getMovies('movie/top_rated'),
            'upcoming' => $this->getMovies('movie/upcoming'),

//            'popular_series' => $this->getSerie('tv/popular'),
            default => response()->json(['message' => 'Invalid filter'], 400),
        };
    }

    private function getFavorites(): JsonResponse
    {
        $favorites = Favorite::query()
            ->select(['uuid as value', 'title as label'])
            ->orderBy('title')
            ->get();

        return response()->json($favorites);
    }

    private function getGenres(): JsonResponse
    {
        $genres = collect($this->tmdbService->getGenres())
            ->map(fn($genre) => [
                'value' => $genre['id'],
                'label' => $genre['name'],
            ]);

        return response()->json($genres);
    }

    private function getMovies(string $endpoint): JsonResponse
    {
        $results = $this->tmdbService->getMoviesList($endpoint);

        if (empty($results['results'])) {
            return response()->json(['message' => 'Nenhum filme encontrado'], 404);
        }

        $movies = collect($results['results'])->map(fn($movie) => [
            'id' => $movie['id'],
            'title' => $movie['title'],
            'poster_path' => isset($movie['poster_path']) ? config('services.tmdb.image_base_url') . '/w500' . $movie['poster_path'] : null,
            'release_date' => $movie['release_date'],
            'vote_average' => $movie['vote_average'],
        ]);

        return response()->json($movies);
    }
}
