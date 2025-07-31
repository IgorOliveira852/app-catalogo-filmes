<?php

namespace App\Http\Resources\Movie;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieCollection extends ResourceCollection
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return $this->collection->map(fn($item) => [
            'id'           => $item['id'] ?? $item->id,
            'title'        => $item['title'] ?? $item['name'] ?? $item->name,
            'poster_path'  => $item['poster_path']
                ? config('services.tmdb.image_base_url') . '/w500' . $item['poster_path']
                : null,
            'overview'     => $item['overview'] ?? '',
            'release_date' => $item['release_date'] ?? $item['first_air_date'],
            'vote_average' => $item['vote_average'] ?? 0,
        ])->all();
    }
}
