<?php

namespace App\Rules;

use App\Services\BookingService;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoBookingConflict implements ValidationRule
{
    protected const int WASH_DURATION = 45;

    public function __construct(
        protected ?int $stationId,
        protected ?string $date,
        protected BookingService $bookingService = new BookingService()
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->stationId || !$this->date) {
            return;
        }

        $startTime = Carbon::parse($value);
        $endTime = (clone $startTime)->addMinutes(self::WASH_DURATION);

        $conflict = $this->bookingService->hasTimeConflict(
            $this->stationId,
            $this->date,
            $startTime->format('H:i'),
            $endTime->format('H:i')
        );

        if ($conflict) {
            $fail(__('validation.booking.conflict_time_slot'));
        }
    }
}
