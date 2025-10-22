<?php

namespace App\Observers;

use App\Models\Booking;
use App\Services\BookingService;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function creating(Booking $booking): void
    {
        $booking->user_id = auth()->id();
        $booking->status = 'status';
    }
}
