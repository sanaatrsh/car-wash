<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LaravelIdea\Helper\App\Models\_IH_Station_QB;
use Spatie\Translatable\HasTranslations;

class Station extends Model
{
    use HasFactory, HasTranslations;
    public array $translatable = ['name', 'address'];

    protected $guarded;

    public function branches(): HasMany|Station
    {
        return $this->hasMany(Station::class, 'parent_station_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'parent_station_id');
    }

    public function scopeNearLocation(Builder $query, float $latitude, float $longitude, float $radiusKm = 100): Builder
    {
        $haversine = "(6371 * acos(
            cos(radians(?))
            * cos(radians(location_x))
            * cos(radians(location_y) - radians(?))
            + sin(radians(?))
            * sin(radians(location_x))
        ))";

        return $query
            ->select('*')
            ->selectRaw("$haversine AS distance", [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radiusKm)
            ->orderBy('distance');
    }
}
