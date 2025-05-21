<?php


use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Filter\GetFiltersController;
use App\Http\Controllers\Movies\FavoriteMovieController;
use App\Http\Controllers\Movies\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('ping', fn() => response()->json(['message' => 'pong']));

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', AuthController::class)->name('auth');

Route::middleware('auth:sanctum')->group(fn() => [
    Route::get('/user', fn(\Illuminate\Http\Request $request) => response()->json($request->user())),

    Route::prefix('movies')->group(fn() => [
        Route::get('search', [MovieController::class, 'search']),
        Route::get('details/{movieId}', [MovieController::class, 'details']),
        Route::get('filters/{slug}', GetFiltersController::class),

        Route::prefix('favorites')->group(fn() => [
            Route::get('/', [FavoriteMovieController::class, 'index']),
            Route::post('/', [FavoriteMovieController::class, 'store']),
            Route::delete('/{movie_id}', [FavoriteMovieController::class, 'destroy']),
        ]),
    ]),
]);
