<?php

namespace App\Providers;

use App\Services\TMDB\TheMovieDBInterface;
use App\Services\TMDB\TheMovieDBService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TheMovieDBInterface::class, TheMovieDBService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
