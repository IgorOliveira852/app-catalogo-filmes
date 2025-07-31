<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\MovieSearchRequest;
use App\Http\Resources\Movie\MovieCollection;
use App\Http\Resources\Movie\MovieDetailResource;
use App\Services\TMDB\TheMovieDBInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MovieController extends Controller
{
    public function __construct(private readonly TheMovieDBInterface $tmdbService)
    {
    }

    public function search(MovieSearchRequest $request): MovieCollection
    {
        $params = $request->only([
                'query', 'page', 'sort_by', 'with_genres',
                'release_date.gte','release_date.lte',
                'vote_average.gte','vote_average.lte'
            ]) + [
                'api_key'  => config('services.tmdb.api_key'),
                'language' => 'pt-BR',
            ];

        $endpoint = $request->input('type', 'movie') === 'tv'
            ? 'discover/tv'
            : 'discover/movie';

        $results = $this->tmdbService->searchWithFilters($endpoint, $params);

        if (empty($results['results'])) {
            abort(404, 'Nenhum resultado encontrado');
        }

        return new MovieCollection(collect($results['results']));
    }

    public function details(int $movieId): MovieDetailResource
    {
        $movie = $this->tmdbService->getMovieDetails($movieId);

        abort_unless(! empty($movie), 404, 'Filme não encontrado');

        return new MovieDetailResource($movie);
    }
}
