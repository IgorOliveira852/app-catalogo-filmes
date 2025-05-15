<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UUID
{
    public static function bootUUID(): void
    {
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
