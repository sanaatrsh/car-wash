<?php

namespace App\Models;

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
}
