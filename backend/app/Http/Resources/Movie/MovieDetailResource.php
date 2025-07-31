<?php

namespace App\Http\Resources\Movie;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieDetailResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id'            => $this['id'],
            'title'         => $this['title'],
            'overview'      => $this['overview'],
            'release_date'  => $this['release_date'],
            'vote_average'  => $this['vote_average'],
            'poster_path'   => $this->makeImageUrl($this['poster_path'], 'w500'),
            'backdrop_path' => $this->makeImageUrl($this['backdrop_path'], 'w780'),
            'genres'        => collect($this['genres'])->pluck('name'),
            'cast'          => $this->formatCast($this['credits']['cast']),
            'trailers'      => $this->formatTrailers($this['videos']['results']),
            'images'        => $this->formatImages($this['images']['backdrops']),
        ];
    }

    private function makeImageUrl(?string $path, string $size): ?string
    {
        if (! $path) return null;
        return config('services.tmdb.image_base_url') . "/{$size}{$path}";
    }

    private function formatCast(array $cast): array
    {
        return collect($cast)
            ->take(10)
            ->map(fn($actor) => [
                'name'         => $actor['name'],
                'character'    => $actor['character'],
                'profile_path' => $this->makeImageUrl($actor['profile_path'], 'w185'),
            ])
            ->all();
    }

    private function formatTrailers(array $videos): array
    {
        return collect($videos)
            ->where('type', 'Trailer')
            ->map(fn($v) => [
                'name' => $v['name'],
                'url'  => "https://www.youtube.com/watch?v={$v['key']}"
            ])
            ->all();
    }

    private function formatImages(array $backdrops): array
    {
        return collect($backdrops)
            ->take(5)
            ->map(fn($img) => $this->makeImageUrl($img['file_path'], 'w780'))
            ->all();
    }
}
