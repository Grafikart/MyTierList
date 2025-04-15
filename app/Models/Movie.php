<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @mixin IdeHelperMovie
 */
class Movie extends Model
{
    protected $guarded = [];

    public static function years(): Collection
    {
        return Movie::selectRaw("strftime('%Y', created_at) as year")
            ->groupByRaw('year')
            ->pluck('year')
            ->values()
            ->map(fn (string $v) => intval($v))
            ->sortByDesc(fn ($v) => $v);
    }
}
