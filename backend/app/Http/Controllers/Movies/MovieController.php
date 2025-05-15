<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Services\TMDB\TheMovieDBInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function __construct(private readonly TheMovieDBInterface $tmdbService)
    {
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query');
        $type = $request->input('type', 'movie');
        $page = $request->input('page', 1);
        $genre = $request->input('genre');
        $sortBy = $request->input('sort_by', 'popularity.desc');
        $releaseDateFrom = $request->input('release_date_gte');
        $releaseDateTo = $request->input('release_date_lte');
        $voteMin = $request->input('vote_average_gte');
        $voteMax = $request->input('vote_average_lte');

        $endpoint = match ($type) {
            'tv' => 'discover/tv',
            default => 'discover/movie'
        };

        $params = [
            'api_key' => config('services.tmdb.api_key'),
            'language' => 'pt-BR',
            'page' => $page,
            'sort_by' => $sortBy,
            'with_genres' => $genre,
            'release_date.gte' => $releaseDateFrom,
            'release_date.lte' => $releaseDateTo,
            'vote_average.gte' => $voteMin,
            'vote_average.lte' => $voteMax,
        ];

        $results = $this->tmdbService->searchWithFilters($endpoint, $params);

        if (empty($results['results'])) {
            return response()->json(['message' => 'Nenhum resultado encontrado'], 404);
        }

        // Mapear os filmes
        $movies = collect($results['results'])->map(fn($movie) => [
            'id' => $movie['id'],
            'title' => $movie['title'] ?? $movie['name'],
            'poster_path' => isset($movie['poster_path']) ? config('services.tmdb.image_base_url') . '/w500' . $movie['poster_path'] : null,
            'overview' => $movie['overview'],
            'release_date' => $movie['release_date'] ?? $movie['first_air_date'],
            'vote_average' => $movie['vote_average'],
        ]);

        return response()->json($movies);
    }

    public function details(int $movieId): JsonResponse
    {
        $movie = $this->tmdbService->getMovieDetails($movieId);

        if (empty($movie)) {
            return response()->json(['message' => 'Filme não encontrado'], 404);
        }

        return response()->json([
            'id' => $movie['id'],
            'title' => $movie['title'],
            'overview' => $movie['overview'],
            'release_date' => $movie['release_date'],
            'vote_average' => $movie['vote_average'],
            'poster_path' => isset($movie['poster_path']) ? config('services.tmdb.image_base_url') . '/w500' . $movie['poster_path'] : null,
            'backdrop_path' => isset($movie['backdrop_path']) ? config('services.tmdb.image_base_url') . '/w780' . $movie['backdrop_path'] : null,
            'genres' => collect($movie['genres'])->pluck('name'),
            'cast' => collect($movie['credits']['cast'])->take(10)->map(fn($actor) => [
                'name' => $actor['name'],
                'character' => $actor['character'],
                'profile_path' => isset($actor['profile_path']) ? config('services.tmdb.image_base_url') . '/w185' . $actor['profile_path'] : null,
            ]),
            'trailers' => collect($movie['videos']['results'])->where('type', 'Trailer')->map(fn($video) => [
                'name' => $video['name'],
                'url' => "https://www.youtube.com/watch?v=" . $video['key']
            ]),
            'images' => collect($movie['images']['backdrops'])->take(5)->map(fn($image) => config('services.tmdb.image_base_url') . '/w780' . $image['file_path']),
        ]);
    }
}
