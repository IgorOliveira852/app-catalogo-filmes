<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Genre extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'name',
        'slug',
        'external_id',
    ];

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Favorite::class, 'favorite_genres');
    }

    protected static function booted(): void
    {
        static::creating(fn($genre) => $genre->slug = Str::slug($genre->name));
        static::updating(fn($genre) => $genre->slug = Str::slug($genre->name));
    }
}
