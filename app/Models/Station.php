<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Station extends Model
{
    use HasFactory, HasTranslations;
    public $translatable = ['name', 'address'];

    protected $guarded;

    public function branches()
    {
        return $this->hasMany(Station::class, 'parent_station_id');
    }

    public function parent()
    {
        return $this->belongsTo(Station::class, 'parent_station_id');
    }

    public function scopeNearLocation(Builder $query, float $latitude, float $longitude, float $radiusKm = 100)
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
