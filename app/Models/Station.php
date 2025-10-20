<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;
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
