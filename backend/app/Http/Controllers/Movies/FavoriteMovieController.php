<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Genre;
use App\Services\TMDB\TheMovieDBInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteMovieController extends Controller
{
    public function __construct(private readonly TheMovieDBInterface $tmdbService)
    {
    }

    public function store(Request $request): JsonResponse
    {
        $favorite = Favorite::query()->create([
            'user_id' => Auth::id(),
            'external_movie_id' => $request->movie_id,
            'title' => $request->title,
            'poster_path' => $request->poster_path ?
                config('services.tmdb.image_base_url') . '/w500' . $request->poster_path :
                null,
            'overview' => $request->overview,
            'release_date' => $request->release_date,
            'vote_average' => $request->vote_average,
        ]);

        $genres = collect($this->tmdbService->getGenres());

        foreach ($request->genre_ids as $genreId) {
            $genre = $genres->firstWhere('id', $genreId);
            if ($genre) {
                $genreModel = Genre::query()->firstOrCreate([
                    'external_id' => $genre['id'],
                    'name' => $genre['name'],
                ]);

                $favorite->genres()->attach($genreModel->id);
            }
        }

        return response()->json([
            'message' => 'Filme favoritado com sucesso!',
            'favorite' => $favorite->load('genres')
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $favorites = Favorite::query()
            ->where('user_id', Auth::id())
            ->search($request->input('search'))
            ->with('genres')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->through(fn(Favorite $favorite) => [
                'id' => $favorite->id,
                'external_movie_id' => $favorite->external_movie_id,
                'title' => $favorite->title,
                'poster_path' => $favorite->poster_path,
                'overview' => $favorite->overview,
                'release_date' => $favorite->release_date,
                'vote_average' => $favorite->vote_average,
                'genres' => $favorite->genres->map(fn($genre) => [
                    'id' => $genre->external_id,
                    'name' => $genre->name,
                ]),
            ]);

        return response()->json($favorites);
    }

    public function destroy(Favorite $favorite): JsonResponse
    {
        $favorite->delete();

        return response()->json([
            'message' => 'Filme removido dos favoritos com sucesso!'
        ]);
    }
}
