<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Favorite extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'user_id',
        'external_movie_id',
        'title',
        'poster_path',
        'overview',
        'release_date',
        'vote_average',
        'genre_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'favorite_genres');
    }

    #[Scope]
    public function search(Builder $query, ?string $searchTerm): Builder
    {
        if ($searchTerm === '') {
            return $query;
        }

        return $query->where(function (Builder $query) use ($searchTerm) {
            $query->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('overview', 'like', "%{$searchTerm}%")
                ->orWhereHas('genres', fn(Builder $query) =>
                $query->where('name', 'like', "%{$searchTerm}%")
                );
        });
    }
}
