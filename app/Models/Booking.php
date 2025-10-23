<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'status',
        'start_time',
        'end_time',
        'date',
        'station_id',
        'wash_type_id',
    ];

    protected $casts = [
        'status' => BookingStatusEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function washType()
    {
        return $this->belongsTo(WashType::class);
    }
}
