<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class BookingService
{
    /**
     * Calculate the end time for a booking based on start time and duration.
     *
     * @param string $startTime The start time in H:i format
     * @param int $durationMinutes The duration in minutes
     * @return string The end time in H:i format
     */
    public function calculateEndTime(string $startTime, int $durationMinutes): string
    {
        return Carbon::createFromFormat('H:i', $startTime)
            ->addMinutes($durationMinutes)
            ->format('H:i');
    }

    /**
     * Check if a booking time slot conflicts with existing bookings.
     *
     * @param int $stationId
     * @param string $date
     * @param string $startTime
     * @param string $endTime
     * @param int|null $excludeBookingId
     * @return bool
     */
    public function hasTimeConflict(int $stationId, string $date, string $startTime, string $endTime, ?int $excludeBookingId = null): bool
    {
        $query = Booking::where('station_id', $stationId)
            ->where('date', $date)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($subQ) use ($startTime, $endTime) {
                        $subQ->where('start_time', '<', $startTime)
                            ->where('end_time', '>', $endTime);
                    });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->exists();
    }
}
